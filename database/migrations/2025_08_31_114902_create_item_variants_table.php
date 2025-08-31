<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'item_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained()->onDelete('cascade'); // belongs to Item
                $table->string('color')->nullable();   // e.g. Red, Blue
                $table->string('size')->nullable();    // e.g. S, M, L, XL
                $table->integer('stock')->default(0);  // stock count
                $table->decimal('price', 10, 2)->nullable(); // can override base price
                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('item_variants');
    }
};
