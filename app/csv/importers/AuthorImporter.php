<?php
use YavorIvanov\CsvImporter\CSVImporter;
class AuthorImporter extends CSVImporter
{
    public $file = 'authors.csv';
    protected $model = 'Author';

    // TODO <Yavor>: Add inline documentation for this.
    protected $primary_key = ['id' => 'csv_id'];

    // TODO <Yavor>: Add inline documentation for this.
    protected $cache_key = ['id' => 'csv_id'];

    // Format: 'csv_column' => ['name' => 'table_column',
    //                          'processors' => ['processor_name' => 'parameters'],
    //                          'validators' => ['validator_name' => 'parameters']
    //                          ]
    // The processors and validators are optional.
    // If you need to pass more than one parameters to a validator or
    // processor, the format becomes:
    //
    // 'processor' => ['processor_name' => ['parameter1', 'parameter2']]
    //
    // You can also omit parameters and use default values if the processor/validator
    // function has them.
    protected $column_mappings = [
        'date of birth' => ['name' => 'date_of_birth', 'processors' => ['null_or_datetime' => 'F j, Y']],
    ];

    protected function get_processors()
    {
        return [
            'null_or_datetime' => function ($v, $fmt='Y-m-d')
            {
                $v = $this->process('string_to_null', $v);
                if ($v == Null)
                    return $v;
                return $this->process('to_datetime', [$v, $fmt]);
            },
        ];
    }

    protected function update($row, $o)
    {
        $o->csv_id = $row['id'];
        $o->first_name = $row["first name"];
        $o->last_name = $row["last name"];
        $o->gender = $row["gender"];
        $o->country_code = $row["country"];
        $o->date_of_birth = $row["date of birth"];
        $o->save();
    }

    protected function import_row($row)
    {
        return Author::create([
            'first_name' => $row["first name"],
            'last_name' => $row["last name"],
            'gender' => $row["gender"],
            'country_code' => $row["country"],
            'date_of_birth' => $row["date of birth"],
            'csv_id' => $row['id'],
        ]);
    }
}
