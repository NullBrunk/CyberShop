<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table -> id();
            
            $table -> unsignedBigInteger('id_contactor');
            $table -> foreign('id_contactor')->references('id')->on('users');
            
            $table -> unsignedBigInteger('id_contacted');
            $table -> foreign('id_contacted')->references('id')->on('users');
            
            
            $table -> longText('content');
            $table -> boolean('readed');
            $table -> dateTime('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('contacts');
    }
};
