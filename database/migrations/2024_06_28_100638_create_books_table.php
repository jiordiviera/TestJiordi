<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->longText('description');
            $table->boolean('status')->default(true);
            $table->date('publication_year')->default(now());
            $table->enum('genre', array_column(\App\BookGenderEnum::cases(), 'value'))->default(\App\BookGenderEnum::NONE->value);
            $table->decimal('price', 8, 2);
            $table->float('quantity')->default(1);
            $table->string('images');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
