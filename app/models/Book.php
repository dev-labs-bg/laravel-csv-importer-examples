<?php

class Book extends Eloquent
{
    protected $table = 'books';

    public function keywords()
    {
        return $this->belongsToMany('Keyword');
    }

    public function authors()
    {
        return $this->belongsToMany('Author');
    }
}
