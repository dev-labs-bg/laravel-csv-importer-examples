<?php
use YavorIvanov\CsvImporter\CSVImporter;
class BookImporter extends CSVImporter
{
    public $file = 'books.csv';
    protected $model = 'Book';

    // TODO <Yavor>: Add inline documentation for this.
    protected $primary_key = ['id' => 'csv_id'];

    // TODO <Yavor>: Add inline documentation for this.
    protected $cache_key = ['id' => 'csv_id'];

    // A list of dependencies to import before attemting to run this importer.
    // Uses the $name property of the importer you wish to depend on.
    // If the importer you wish to use does not define a $name property, you may
    // use the part of the class name that comes before "Importer". For example,
    // this importer can be referenced by adding 'book' in a $deps array.
    protected static $deps = ['author'];

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
        'release date' => ['name' => 'release_date', 'processors' => ['null_or_datetime' => 'Y']],
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
        $o->title = $row["title"];
        $o->ISBN = $row['ISBN'];
        $o->release_date = $row["release date"];
        $o->save();
        $author = $this->get_from_context('author', $row["author"]);
        $book->authors()->sync([$author->id], false);
    }

    protected function import_row($row)
    {
        $book = Book::create([
            'title' => $row["title"],
            'release_date' => $row["release date"],
            'ISBN' => $row['ISBN'],
            'genre' => 1, // FIXME <Yavor>: This is now a pivot.
            'csv_id' => $row['id'],
        ]);
        $author = $this->get_from_context('author', $row["author"]);
        $book->authors()->sync([$author->id], false);
        return $book;
    }
}
