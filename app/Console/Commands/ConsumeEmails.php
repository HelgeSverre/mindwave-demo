<?php

namespace App\Console\Commands;

use App\MailMessage;
use App\Models\Email;
use App\Models\User;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\ListMessagesResponse;
use Google\Service\Gmail\Message;
use Illuminate\Console\Command;
use Mindwave\Mindwave\Document\Data\Document;
use Mindwave\Mindwave\Facades\Mindwave;
use Mindwave\Mindwave\Facades\Vectorstore;
use Mindwave\Mindwave\Support\TextUtils;
use Throwable;

class ConsumeEmails extends Command
{
    protected $signature = 'consume:emails';

    public function handle()
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessType('offline');

        foreach (User::all() as $user) {

            $this->info("Deleting existing emails from user: {$user->id}");
            Email::where('user_id', $user->id)->delete();

            $this->info("Starting indexing of emails from user: {$user->id}");

            $client->setAccessToken($user->google_token);
            $service = new Gmail($client);

            $this->info('Fetching last 100 email ids...');
            /** @var ListMessagesResponse $emails */
            $emails = $service->users_messages->listUsersMessages('me', [
                'includeSpamTrash' => false,
                'pageToken' => null,
                'maxResults' => 100,
            ]);
            $this->info('Fetched!');

            /** @var Message $email */
            $client->setDefer(true);

            $batch = $service->createBatch();

            foreach ($emails as $email) {
                /** @noinspection PhpParamsInspection */
                $batch->add($service->users_messages->get('me', $email->id));
            }

            $this->info('Gathering messages in batch!');
            $response = $batch->execute();
            $this->info('Done!');

            $client->setDefer(false);

            foreach ($response as $message) {

                $mail = MailMessage::fromGmailMessage($message);

                $this->info("Parsed email: {$mail->subject}");

                Email::create([
                    'user_id' => $user->id,
                    'from' => $mail->from,
                    'to' => $mail->to,
                    'reply_to' => $mail->replyTo,
                    'subject' => $mail->subject,
                    'body_html' => $mail->bodyHtml,
                    'body_text' => $mail->bodyText,
                    'received_at' => $mail->receivedAt,
                ]);
            }
        }

        foreach (Email::latest()->limit(50)->get() as $emailModel) {

            $this->info("Consuming: {$emailModel->subject}");

            try {
                Mindwave::brain()->consume(new Document(
                    content: TextUtils::cleanHtml($emailModel->body_html),
                    metadata: array_filter([
                        'from' => $emailModel->from,
                        'to' => $emailModel->to,
                        'reply_to' => $emailModel->replyTo,
                        'subject' => $emailModel->subject,
                        'received_at' => $emailModel->receivedAt,
                    ])
                ));
                $this->comment('vectorstore item count: '.Vectorstore::itemCount());
            } catch (Throwable $exception) {
                $this->warn('Error: '.$exception->getMessage());
            }
        }
    }
}
