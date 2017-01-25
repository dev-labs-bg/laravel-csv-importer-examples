<?php
use YavorIvanov\CsvImporter\CSVExporter;
class BookExporter extends CSVExporter
{
    public $file = 'books.csv';
    protected $model = 'Book';
    protected $column_mapping = [
        ['csv_id' => 'id'],
        ['authors()->first()->csv_id' => 'author'],
        'title',
        ['release_date' => ['release date' => ['datetime_to_format' => 'Y']]],
        'ISBN',
    ];

    protected function get_processors()
    {
        return [
            'datetime_to_format' => function ($v, $fmt='Y-m-d')
            {
                $v = new DateTime($v);
                return $v->format($fmt);
            },
        ];
    }
}
