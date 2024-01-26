<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('question_banks', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("creator_id");
            $table->foreign("creator_id")->references("id")->on("users")->cascadeOnDelete();
            $table->unsignedBigInteger("space_id");
            $table->foreign("space_id")->references("id")->on("spaces")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_banks', function (Blueprint $table) {
            $table->dropForeign(['creator_id', 'space_id']);
        });
        Schema::dropIfExists('question_banks');
    }
};
