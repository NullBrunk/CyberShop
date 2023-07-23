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
            $table -> id();
            $table -> unsignedBigInteger('id_user');
            $table -> foreign('id_user') -> references('id') -> on('users') -> onDelete('cascade');
            $table -> string('name', 45);
            $table -> string('price', 20);
            $table -> longText('descr');
            $table -> longText('class', 45);
            $table -> string('image', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('products');
    }
};
