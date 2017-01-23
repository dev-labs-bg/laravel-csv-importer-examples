<?php

class Author extends Eloquent
{
    use YavorIvanov\CsvImporter\CSVReferenceTrait;

    protected $table = 'authors';

    public function books()
    {
        return $this->belongsToMany('Book');
    }
}
