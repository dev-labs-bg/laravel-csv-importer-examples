<?php
use YavorIvanov\CsvImporter\CSVImporter;
class BookImporter extends CSVImporter
{
    public $file = 'books.csv';
    protected $model = 'Book';

    // TODO <Yavor>: Add inline documentation for this.
    protected $primary_key = ['id' => 'csv_id'];

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
            'csv_id' => $row['id'],
        ]);

        // You may reference model instances from your importer's dependencies
        // with the 'get_from_context()' function. The format is:
        //
        //   get_from_context('dep_name', 'value')
        //
        // You can think of the context as a copy of the cache of all dependencies
        // your importer has. The context hash is keyed by whatever the $foreign_key is
        // set on the dependency you're referencing. If no $foreign_key is declared, the
        // context is keyed by the $cache_key instead.
        // Note: The csv_column values are used as the cache key in both cases.
        // (This works just like the regular model caching.)
        $author = $this->get_from_context('author', $row["author"]);
        $book->authors()->sync([$author->id], false);
        return $book;
    }
}
