<?php
use YavorIvanov\CsvImporter\CSVExporter;
class AuthorExporter extends CSVExporter
{
    public $file = 'authors.csv';
    protected $model = 'Author';
    protected $column_mapping = [
        ['csv_id' => 'id'],
        ['first_name' => 'first name'],
        ['last_name' => 'last name'],
        'gender',
        ['country_code' => 'country'],
        ['date_of_birth' => ['date of birth' => ['datetime_to_format' => 'F j, Y']]],
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
