<?php
use YavorIvanov\CsvImporter\CSVExporter;
class GenreExporter extends CSVExporter
{
    public $file = 'genres.csv';
    protected $model = 'Genre';
    protected $column_mapping = [
        ['csv_id' => 'id'],
        'name',
    ];
}
