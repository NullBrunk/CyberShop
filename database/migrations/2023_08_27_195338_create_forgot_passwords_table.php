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
        Schema::create('forgot_passwords', function (Blueprint $table) {
            $table->id();
            
            $table -> unsignedBigInteger('id_user');
            $table -> foreign('id_user') -> references('id') -> on('users') -> onDelete('cascade');

            $table -> text("reset_code");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forgot_passwords');
    }
};
