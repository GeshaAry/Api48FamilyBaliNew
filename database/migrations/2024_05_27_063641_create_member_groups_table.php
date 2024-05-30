<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_groups', function (Blueprint $table) {
            $table->id('user_id');
            $table->bigInteger('member_id')->nullable();
            $table->string('member_id_groups')->nullable();
            $table->string('user_fullname')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_password')->nullable();
            $table->string('user_gender')->nullable();
            $table->string('user_telephone')->nullable();
            $table->string('user_picture')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_groups');
    }
};
