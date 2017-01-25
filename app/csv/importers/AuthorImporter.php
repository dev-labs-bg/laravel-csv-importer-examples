<?php
use YavorIvanov\CsvImporter\CSVImporter;
class AuthorImporter extends CSVImporter
{
    public $file = 'authors.csv';

    // The model name you wish to import. The importer uses this to cathe
    // the database rows already in the database, and those written during
    // import.
    // Corresponds to the name of your model class.
    protected $model = 'Author';

    // The importer selects all rows from the models tables in the beginning of the
    // import and caches them according to a unique CSV column. There are two reasons
    // why you may want this:
    //   1) The importer will skip over csv rows that are already in the database.
    //      The importer can check if each CSV record has a corresponding record in
    //      the database table by using the csv column value as a hash key to the cache.
    //
    //   2) You will be able to reference previous rows in the CSV.
    //      You can use the 'get_from_cache($hash)' function to retrieve the model
    //      instance which is cached by that key. This is useful when importing
    //      self-referential CSVs; for example a tree structure in the following format:
    //      [id, name, parent_id]
    protected $cache_key = ['id' => 'csv_id'];

    // Format: 'csv_column' => ['name' => 'table_column',
    //                          'processors' => ['processor_name' => 'parameter'],
    //                          'validators' => ['validator_name' => 'parameter']
    //                          ]
    // The processors and validators are optional.
    // If you need to pass more than one parameters to a validator or
    // processor, the format becomes:
    //
    // 'processor' => ['processor_name' => ['parameter1', 'parameter2']]
    //
    // You can also use more than one processor:
    // 'processor' => ['processor1' => ['parameter1', 'parameter2'], 'processor2' => 'param', 'processor3']
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
        $o->csv_id        = $row['id'];
        $o->first_name    = $row["first name"];
        $o->last_name     = $row["last name"];
        $o->gender        = $row["gender"];
        $o->country_code  = $row["country"];
        $o->date_of_birth = $row["date of birth"];
        $o->save();
    }

    protected function import_row($row)
    {
        return Author::create([
            'first_name'    => $row["first name"],
            'last_name'     => $row["last name"],
            'gender'        => $row["gender"],
            'country_code'  => $row["country"],
            'date_of_birth' => $row["date of birth"],
            'csv_id'        => $row['id'],
        ]);
    }
}
