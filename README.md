![Mindwave](.github/header.png)

# Mindwave Demo Application

This is a demo application showcasing the real-world usage for
the [Mindwave Package](https://github.com/helgesverre/mindwave) for Laravel.

This demo application allows users to upload or index their own files, and then interact with an AI chatbot about the
contents of those files.

## What is Mindwave?

Mindwave is a Laravel package that lets you easily build AI-powered chatbots, agents, and document question and
answering (Q&A) functionality into your application.

With Mindwave, you can incorporate the power of OpenAI's state-of-the-art language models, Pinecone's vector search
capabilities and your own custom "tools" to create intelligent software applications.

[Learn more here](https://mindwave.no)

## Features

- Document Upload: Users can upload their documents to the application.
- Document Chunking: The uploaded documents are automatically split into smaller chunks for efficient indexing and
  analysis.
- Vectorstore Indexing: The chunks of documents are indexed by a vector store, enabling fast retrieval and processing of
  information.
- Chatbot with conversational memory: Engage in a conversation with the AI-powered chatbot to explore and discuss the
  content of the
  uploaded documents.

## Getting Started

To get started with the Mindwave Demo Application, follow these steps:

```shell
git clone git@github.com:HelgeSverre/mindwave-demo.git
cd mindwave-demo
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
yarn install
yarn build # or dev
```

**Note:** Make sure to update your `.env` file with the correct database credentials and
your [OpenAI API Key](https://platform.openai.com/account/api-keys).
