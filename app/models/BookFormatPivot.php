<?php

class BookFormatPivot extends Eloquent
{
    use YavorIvanov\CsvImporter\CSVReferenceTrait;
    protected $table = 'book_format';

    public function book()
    {
        return $this->belongsTo('Book');
    }

    public function format()
    {
        return $this->belongsTo('Format');
    }
}
