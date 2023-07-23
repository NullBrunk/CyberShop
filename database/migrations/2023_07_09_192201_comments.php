<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table -> id();
            
            $table -> unsignedBigInteger('id_user');
            $table -> foreign('id_user') -> references('id') -> on('users') -> onDelete('cascade');
            
            $table -> unsignedBigInteger('id_product');
            $table -> foreign('id_product') -> references('id') -> on('products') -> onDelete('cascade');
            

            $table -> string('title', 45);
            $table -> longText('content');
            $table -> smallInteger('rating');
            $table -> dateTime('writed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('comments');
    }
};
