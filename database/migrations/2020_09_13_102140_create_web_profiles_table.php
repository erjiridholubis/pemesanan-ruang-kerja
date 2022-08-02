<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_profile', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('thumbnail');
            $table->string('title');
            $table->string('slogan');
            $table->text('description');
            $table->string('version');
            $table->string('phone');
            $table->string('email');
            $table->string('ig');
            $table->string('line');
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
        Schema::dropIfExists('web_profiles');
    }
}
