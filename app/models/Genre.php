<?php

class Genre extends Eloquent
{
    use YavorIvanov\CsvImporter\CSVReferenceTrait;
    protected $table = 'genres';

    public function books()
    {
        return $this->belongsToMany('Book')->withTimestamps();
    }
}
