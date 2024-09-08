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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('id_user')->unsigned();
            $table->string('status');
            $table->string('qrcode');
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users');
        });

       Schema::create('votes_details', function (Blueprint $table) {
            $table->id();
            $table->string('name_choice');
            $table->string('description');
            $table->unsignedBigInteger('id_votes')->unsigned();
            $table->foreign('id_votes')->references('id')->on('votes');
            $table->timestamps();
            $table->softDeletes();
       });

       Schema::create('votes_enrolls', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->uniqid();
            $table->unsignedBigInteger('id_votes')->unsigned();
            $table->unsignedBigInteger('id_votes_details')->unsigned();
            $table->timestamps();
            $table->foreign('id_votes')->references('id')->on('votes');
            $table->foreign('id_votes_details')->references('id')->on('votes_details');

       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
        Schema::dropIfExists('votes_detail');
        Schema::dropIfExists('vote');
    }
};
