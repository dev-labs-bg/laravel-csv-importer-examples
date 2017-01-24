<?php
use YavorIvanov\CsvImporter\CSVImporter;
class BookFormatImporter extends CSVImporter
{
    public $file = 'books_to_formats.csv';
    protected static $deps = ['book', 'format'];
    protected $model = 'Book';

    // Sometimes you'll want to import a many to many relationship between two models.
    // You can create a pivot CSV table and import that to the database with minimum fuss.
    protected function import_row($row)
    {
        $format = $this->get_from_context('format', $row['format']);
        $book = $this->get_from_context('book', $row['book']);
        $book->formats()->sync([$format->id], false);
    }
}
