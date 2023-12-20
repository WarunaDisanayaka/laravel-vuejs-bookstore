<?php

use App\Models\User;
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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 400);
            $table->integer('quantity');
            $table->longText('description')->nullable();
            $table->boolean('published')->default(0);
            $table->boolean('inStock')->default(0);
            $table->decimal('price', 10, 2);

            // New columns
            $table->string('publisher')->nullable();
            $table->string('isbn')->nullable();
            $table->string('edition')->nullable();
            $table->integer('number_of_pages')->nullable();

            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();

            $table->softDeletes();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
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
