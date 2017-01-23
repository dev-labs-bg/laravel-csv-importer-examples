<?php

class Keyword extends Eloquent
{
    protected $table = 'keywords';

    public function books()
    {
        return $this->belongsToMany('Book');
    }
}
