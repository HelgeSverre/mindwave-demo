<?php

namespace App\Console\Commands;

use App\Models\Email;
use App\TextUtils;
use HelgeSverre\Brain\Facades\Brain;
use HelgeSverre\Milvus\Facades\Milvus;
use Illuminate\Console\Command;

class IndexEmails extends Command
{
    protected $signature = 'email:index';

    public function handle()
    {
        $collectionName = 'emails';
        $collections = Milvus::collections()->list()->collect('data');

        if ($collections->isEmpty()) {
            $this->info("Creating collection '$collectionName'");
            $response = Milvus::collections()->create(
                collectionName: 'emails',
                dimension: 1536,
                description: 'Indexed emails from Mindwave demo app',
            );

            if ($response->failed()) {
                $this->error("Failed to create collection '$collectionName'");

                return;
            } else {
                $this->info("Collection '$collectionName' created");
            }
        } else {
            $this->info("Collection '$collectionName' already exists");
        }

        $emails = Email::latest()->limit(1000)->get();

        if ($emails->isEmpty()) {
            $this->warn('No emails to index');

            return;
        }

        foreach ($emails as $email) {

            $this->info("Indexing email: {$email->id} - {$email->subject}");

            $embedding = Brain::embedding(
                TextUtils::cleanHtml($email->body_html)
            );

            $response = Milvus::vector()->insert(
                collectionName: $collectionName,
                data: [
                    'vector' => $embedding,
                    'from' => $email->from,
                    'to' => $email->to,
                    'reply_to' => $email->reply_to,
                    'subject' => $email->subject,
                    'received_at' => $email->received_at,
                ],
            );

            $insertId = $response->json('data.insertIds.0');

            if ($response->failed() || $insertId == null) {
                $this->error("Failed to index email: {$email->id} - {$email->subject}");

                continue;
            }

            $this->info("Indexed email: {$email->id} with vector id: $insertId");

            $email->update([
                'vector_db_id' => $insertId,
            ]);
        }
    }
}
