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
        Schema::create('products', function (Blueprint $table) {
            //primary id
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('description');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->integer('stock')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
