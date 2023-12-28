<?php

namespace App\Console\Commands;

use App\MailMessage;
use App\Models\Email;
use App\Models\User;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use Google_Service_Gmail_Message;
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

            // TODO: use the pagToken to fetch the next page until there is no pageToken

            $messageIds = [];
            $nextPageToken = null;

            do {
                $response = $service->users_messages->listUsersMessages('me', [
                    'q' => 'after:'.now()->subMonths(3)->getTimestamp(),
                    'includeSpamTrash' => false,
                    'pageToken' => $nextPageToken,
                    'maxResults' => 500,
                ]);

                $nextPageToken = $response->nextPageToken;

                $this->info("Paginating using $nextPageToken, collected ".count($messageIds).' message Ids.');

                $newIds = collect($response->getMessages())
                    ->map(fn (Google_Service_Gmail_Message $message) => $message->getId())
                    ->toArray();

                $messageIds = array_merge($messageIds, $newIds);
            } while ($nextPageToken != null);

            $this->info('Fetched all pages');

            /** @var Message $email */
            $client->setDefer(true);

            foreach (collect($messageIds)->chunk(100) as $batchIndex => $batchIds) {

                $this->info("Gathering message batch: {$batchIndex}");

                $batch = $service->createBatch();
                foreach ($batchIds as $messageId) {
                    $batch->add($service->users_messages->get('me', $messageId));
                }

                $response = $batch->execute();

                $this->info('Gathered batch, parsing...');

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

                $this->info('Parsed batch');
            }

            $client->setDefer(false);
        }
    }
}
