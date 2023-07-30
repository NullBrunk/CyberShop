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
        Schema::create('tmp_images', function (Blueprint $table) {
            $table->id();
            $table->string("file");
            $table->string("folder");
            $table -> string("checksum", 120);
            $table -> boolean("is_main");
            $table -> string("extension", 10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tmp_images');
    }
};
