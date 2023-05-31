<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default VectorStore
    |--------------------------------------------------------------------------
    |
    | todo: taylorized description
    */

    'default' => env('MINDWAVE_VECTORSTORE', 'pinecone'),

    /*
    |--------------------------------------------------------------------------
    | VectorStores
    |--------------------------------------------------------------------------
    |
    | todo: taylorized description
    */
    'vectorstores' => [
        'array' => [
            // Has no configuration, used for testing
        ],

        'file' => [
            'path' => env('MINDWAVE_VECTORSTORE_PATH', storage_path('mindwave/vectorstore.json')),
        ],

        'pinecone' => [
            'api_key' => env('MINDWAVE_PINECONE_API_KEY'),
            'environment' => env('MINDWAVE_PINECONE_ENVIRONMENT'),
            'index' => env('MINDWAVE_PINECONE_INDEX'),
        ],

        'weaviate' => [
            'api_url' => env('MINDWAVE_WEAVIATE_URL'),
            'api_token' => env('MINDWAVE_WEAVIATE_API_TOKEN'),
            'index' => env('MINDWAVE_WEAVIATE_INDEX'),
            'additional_headers' => [],
        ],
    ],
];
