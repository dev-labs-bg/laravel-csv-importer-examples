<?php
use YavorIvanov\CsvImporter\CSVExporter;
class BookFormatExporter extends CSVExporter
{
    public $file = 'books_to_formats.csv';
    public $model = 'BookFormatPivot';
    protected $column_mapping = [
        ['book()->first()->csv_id'   => 'book'],
        ['format()->first()->csv_id' => 'format'],
    ];
}
