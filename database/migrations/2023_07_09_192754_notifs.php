<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('notifs', function (Blueprint $table) {
            $table -> id();
            
            $table -> unsignedBigInteger('id_user');
            $table -> foreign('id_user')->references('id')->on('users');
            
            $table -> string('type', 10);
            $table -> string('icon', 55);
            $table -> string('name', 55);

            $table -> longText('content');
            $table -> longText('link');
                        
            $table -> integer('moreinfo');
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
