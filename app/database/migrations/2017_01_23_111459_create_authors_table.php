<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorsTable extends Migration
{
    public function up()
    {
        Schema::create('authors', function(Blueprint $t)
        {
            $t->increments('id');
            $t->string('first_name');
            $t->string('last_name');
            $t->enum('gender', ['m', 'f']);
            $t->date('date_of_birth');
            $t->string('country_code');
            $t->integer('csv_id')->unique();
            $t->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('authors');
    }
}
