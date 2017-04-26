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

    // The importer reads these mappings and converts the CSV columns to the
    // database table format. The full format for the column mappings is as follows:
    //
    // Format: ['csv_column' => ['name' => 'table_column',
    //                          'processors' => ['processor_name' => 'parameters'],
    //                          'validators' => ['validator_name' => 'parameters']
    //                          ]]
    //
    // The processors and validators are optional. If you need to pass more than one
    // parameters to a validator or processor, the format becomes:
    //
    // 'processor' => ['processor_name' => ['parameter1', 'parameter2']]
    //
    // You can also omit parameters and use default values if the processor/validator
    // function has them.
    //
    // It's more common to only need a few options from the column mapping.
    // In those cases you can omit what you don't need. Here are some examples of this:
    //
    //   - Mapping a csv column to a model property without preprocessing:
    //     ['csv_column' => 'model_property'],
    //   - Postprocessing without parameters (or defaults):
    //     ['csv_column' => ['name' => 'model_property', 'processors' => 'pre-processor_name']],
    //   - The model property name coincides with the csv column name:
    //     'csv_column',
    //   - Using multiple pre-processors with parameters:
    //     ['csv_column' => ['name' => 'model_property' => [
    //                             'processor1' => ['param1', 'param2'],
    //                             'processor2' => 'single_param'
    //     ]]],
    //   - Multiple pre-processors without parameters:
    //     ['csv_column' => ['name' => 'model_propetry' => ['processor1', 'processor2']]],
    //
    // The 'model_property' isn't restricted to just table colmuns. Model mutators are perfectly valid.
    protected $column_mapping = [
        ['id' => 'csv_id'],
        'title',
        'ISBN',
        ['release date' => ['name' => 'release_date', 'processors' => ['null_or_datetime' => 'Y']]],
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

    // The package uses the column mappings to figure out how to update/create the model.
    // Yet, sometimes, the mappings aren't flexible enough.
    //
    // In such cases,you can override the import_row() and update() functions.
    //
    // update() gets passed a single CSV row and the corresponding model instance from
    // the database. It expects a model instance to be returned.
    //
    // import_row() gets passed a single CSV row. It expects a new model instance to be returned.
    //
    // NOTE: If you choose to override these functions, the importer will not process
    // the column mappings, upnless you call the parent::update() or parent::import_row() functions.
    // This way you can mix custom row generation logic with column mappings, or skip the
    // automatic mappnig entirely.
    protected function update($row, $o)
    {
        $o = parent::update($row, $o);
        $o->save();
        $author = $this->get_from_context('author', $row["author"]);
        $o->authors()->sync([$author->id], false);
        return $o;
    }

    protected function import_row($row)
    {
        $o = parent::import_row($row);
        $o->save();

        // You may reference model instances from your importer's dependencies
        // with the 'get_from_context()' function. The format is:
        //
        //   get_from_context('dep_name', 'value')
        //
        // You can think of the context as a copy of the cache of all dependencies
        // your importer has. The context hash is keyed by whatever the $foreign_key is
        // set on the dependency you're referencing. If no $foreign_key is declared, the
        // context is keyed by the $cache_key instead.
        //
        // Note: The csv_column values are used as the cache key in both cases.
        // (This works just like the regular model caching.)
        $author = $this->get_from_context('author', $row["author"]);
        $o->authors()->sync([$author->id], false);
        return $o;
    }
}
