<?php

class Format extends Eloquent
{
    use YavorIvanov\CsvImporter\CSVReferenceTrait;
    protected $table = 'formats';

    public function books()
    {
        return $this->belongsToMany('Book')->withTimestamps();
    }
}
