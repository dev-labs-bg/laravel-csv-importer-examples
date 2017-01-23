<?php

class Book extends Eloquent
{
    protected $table = 'book';

    public function keywords()
    {
        return $this->belongsToMany('Keyword');
    }

    public function authors()
    {
        return $this->belongsToMany('Author');
    }
}
