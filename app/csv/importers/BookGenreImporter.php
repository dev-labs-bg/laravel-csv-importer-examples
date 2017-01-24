<?php
use YavorIvanov\CsvImporter\CSVImporter;
class BookGenreImporter extends CSVImporter
{
    public $file = 'books_to_genres.csv';
    protected $model = 'BookGenrePivot';
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
                'book_id' => $book_id,
                'genre_id' => $genre_id,
            ]);
        }
        return $pivoted_row;
    }

    protected function import_row($row)
    {
        $genre = $this->get_from_context('genre', $row['genre_id']);
        $book = $this->get_from_context('book', $row['book_id']);
        $book->genres()->sync([$genre->id], false);
    }
}
