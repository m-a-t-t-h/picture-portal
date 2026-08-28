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
        Schema::create('tag_chain', function (Blueprint $table) {
            $table->integer("tag_id");
            $table->string("tag_name");
            $table->string("tag_path");
            $table->string("tag_chain");
            $table->integer("tag_depth");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_chain');
    }
};
