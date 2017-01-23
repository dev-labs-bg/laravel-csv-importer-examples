<?php

class Keyword extends Eloquent
{
    use YavorIvanov\CsvImporter\CSVReferenceTrait;
    protected $table = 'keywords';

    public function books()
    {
        return $this->belongsToMany('Book');
    }
}
