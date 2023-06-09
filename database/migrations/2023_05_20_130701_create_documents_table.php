<?php

use App\Enums\DocumentState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path')->nullable();
            $table->string('type')->nullable();

            $table->string('mime')->nullable();
            $table->integer('size')->nullable();

            $table->longText('text')->nullable();
            $table->string('state')->nullable()->default(DocumentState::pending->value);
            $table->timestamps();
        });
    }
};
