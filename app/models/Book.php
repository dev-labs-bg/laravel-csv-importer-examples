<?php

class Book extends Eloquent
{
    use YavorIvanov\CsvImporter\CSVReferenceTrait;
    protected $table = 'books';

    public function genres()
    {
        return $this->belongsToMany('Genre')->withTimestamps();
    }

    public function authors()
    {
        return $this->belongsToMany('Author')->withTimestamps();
    }

    public function formats()
    {
        return $this->belongsToMany('Format')->withTimestamps();
    }
}
