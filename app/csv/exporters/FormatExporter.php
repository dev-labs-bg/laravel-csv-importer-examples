<?php
use YavorIvanov\CsvImporter\CSVExporter;
class FormatExporter extends CSVExporter
{
    public $file = 'formats.csv';
    protected $model = 'Format';
    protected $column_mapping = [
        ['csv_id' => 'id'],
        ['type' => 'format'],
    ];
}
