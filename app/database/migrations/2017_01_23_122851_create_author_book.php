<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorBook extends Migration
{
    public function up()
    {
        Schema::create('author_book', function(Blueprint $t)
        {
            $t->increments('id');
            $t->integer('author_id');
            $t->integer('book_id');
            $t->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('author_book');
    }
}
