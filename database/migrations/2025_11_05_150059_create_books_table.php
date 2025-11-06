<?php

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
        // 4. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->timestamps();
        });

        // 5. Books
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('author', 255);
            $table->string('number_book', 255);
            $table->string('publisher', 255)->nullable();
            $table->text('cover')->nullable();
            $table->integer('publication_year')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        // 6. Borrowings
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->string('borrow_code', 255);
            $table->date('loan_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['loaned', 'returned'])->default('loaned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
        Schema::dropIfExists('books');
        Schema::dropIfExists('categories');
    }
};
