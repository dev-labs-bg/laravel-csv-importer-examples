<?php

class Author extends Eloquent
{
    protected $table = 'authors';

    public function books()
    {
        return $this->belongsToMany('Book');
    }
}
