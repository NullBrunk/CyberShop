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
        Schema::create('likes', function (Blueprint $table) {

            $table -> unsignedBigInteger('id_user');
            $table -> foreign('id_user') -> references('id') -> on('users') -> onDelete('cascade');
            
            $table -> unsignedBigInteger('id_comment');
            $table -> foreign('id_comment') -> references('id') -> on('comments') -> onDelete('cascade');
            
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
