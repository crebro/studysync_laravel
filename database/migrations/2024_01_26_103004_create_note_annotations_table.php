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
        Schema::create('note_annotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('note_id');
            $table->unsignedBigInteger('creator_id');
            $table->string('annotation_identifier')->unique();

            $table->tinyText('content');
            $table->integer('page');
            $table->string('yloc');

            $table->foreign('note_id')->references('id')->on('notes')->cascadeOnDelete();
            $table->foreign('creator_id')->references('id')->on('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('note_annotations', function (Blueprint $table) {
            $table->dropForeign(['note_id', 'creator_id']);
        });
        Schema::dropIfExists('note_annotations');
    }
};
