<?php
use YavorIvanov\CsvImporter\CSVImporter;
class BookFormatImporter extends CSVImporter
{
    public $file = 'books_to_formats.csv';

    // A list of dependencies to import before attemting to run this importer.
    // Uses the $name property of the importer you wish to depend on.
    // If the importer you wish to use does not define a $name property, you may
    // use the part of the class name that comes before "Importer". For example,
    // this importer can be referenced by adding 'bookformat' in a $deps array.
    protected static $deps = ['book', 'format'];
    protected $model = 'Book';

    // Sometimes you'll want to import a many to many relationship between two models.
    // You can create a pivot CSV table and import that to the database with minimum fuss.
    protected function import_row($row)
    {
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
        $format = $this->get_from_context('format', $row['format']);
        $book   = $this->get_from_context('book',   $row['book']);
        $book->formats()->sync([$format->id], false);
    }
}
