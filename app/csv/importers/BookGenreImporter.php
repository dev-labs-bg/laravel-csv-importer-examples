<?php
use YavorIvanov\CsvImporter\CSVImporter;
class BookGenreImporter extends CSVImporter
{
    public $file = 'books_to_genres.csv';
    protected $model = 'BookGenrePivot';

    // A list of dependencies to import before attemting to run this importer.
    // Uses the $name property of the importer you wish to depend on.
    // If the importer you wish to use does not define a $name property, you may
    // use the part of the class name that comes before "Importer". For example,
    // this importer can be referenced by adding 'bookgenre' in a $deps array.
    protected static $deps = ['book', 'genre'];

    // Sometimes your pivot CSV table nicely mirrors the database table, like in books_to_formats.csv.
    // Other times, your many to many pivot CSV may come in a weird, multiple column format (genres_to_formats.csv).
    // Although changing the CSV format to be in tune with the database table layout would be ideal,
    // you may not always have that luxury (widely used legacy formats).
    //
    // When this happens, you can do some preprocessing of the individual rows before the
    // import function gets to read them.
    //
    // This function takes a row in the [book_id, genre1, genre2, ... genreN] format
    // and replaces it with multiple rows of [book_id, genre1] ... [book_id, genreN] tuples.
    // The format for exporting is ['column1_name' => 'value' ... 'columnN_name' => 'value']
    //
    // The pivot step is run before column processors and validators.
    protected function pivot_row($row)
    {
        $pivoted_row = [];
        $book_id = $row['book'];

        // Loops over the genre columns only, as there is only one book column.
        foreach (array_filter(array_slice($row, 1)) as $genre_id)
        {
            array_push($pivoted_row, [
                'book_id'  => $book_id,
                'genre_id' => $genre_id,
            ]);
        }
        return $pivoted_row;
    }

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
        $genre = $this->get_from_context('genre', $row['genre_id']);
        $book  = $this->get_from_context('book', $row['book_id']);
        $book->genres()->sync([$genre->id], false);
    }
}
