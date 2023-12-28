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

class ImportEmails extends Command
{
    protected $signature = 'email:import';

    // TODO: Build a simplified Gmail client wrapper that supports batching and such and make it available as a package.
    protected function buildGmailClient($token): Client
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessType('offline');
        $client->setAccessToken($token);

        return $client;
    }

    public function handle()
    {

        foreach (User::all() as $user) {

            $this->info("Deleting existing emails from user: {$user->id}");
            Email::where('user_id', $user->id)->delete();

            $this->info("Starting indexing of emails from user: {$user->id}");

            $client = $this->buildGmailClient($user->google_token);

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
    }
}
