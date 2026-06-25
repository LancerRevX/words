<?php

use App\Enums\EntryClass;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
        });

        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
        });

        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('language_id')->constrained();
            $table->enum('class', EntryClass::cases());
            $table->string('spelling');
            $table->string('pronounciation')->nullable();
        });

        Schema::create('definitions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('entry_id')->constrained();
            $table->text('text');
            $table->foreignId('source_id')->nullable()->constrained();
            $table->string('image_url')->nullable();
        });

        Schema::create('examples', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('definition_id')->constrained();
            $table->text('text');
            $table->foreignId('source_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
