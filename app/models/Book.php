<?php

class Book extends Eloquent
{
    use YavorIvanov\CsvImporter\CSVReferenceTrait;
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
