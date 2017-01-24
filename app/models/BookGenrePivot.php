<?php

class BookGenrePivot extends Eloquent
{
    use YavorIvanov\CsvImporter\CSVReferenceTrait;
    protected $table = 'book_genre';

    public function books()
    {
        return $this->belongsTo('Book')->withTimestamps();
    }

    public function genre()
    {
        return $this->belongsTo('Genre')->withTimestamps();
    }
}
