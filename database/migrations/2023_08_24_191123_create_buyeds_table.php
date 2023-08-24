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
        Schema::create('buyeds', function (Blueprint $table) {
            $table->id();
            $table -> unsignedBigInteger('id_buyer');
            $table -> foreign('id_buyer') -> references('id') -> on('users') -> onDelete('cascade');

            $table -> unsignedBigInteger('id_seller');
            $table -> foreign('id_seller') -> references('id') -> on('users') -> onDelete('cascade');

            $table -> unsignedBigInteger('id_product');
            $table -> foreign('id_product') -> references('id') -> on('products') -> onDelete('cascade');
       
            $table -> integer("quantity");
            $table -> float("price");


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyeds');
    }
};
