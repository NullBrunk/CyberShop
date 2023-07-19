<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
Schema::table('contacts', function (Blueprint $table) {
    $table->string('type', '5') -> default("text");
});