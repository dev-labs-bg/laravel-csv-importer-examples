<?php
use YavorIvanov\CsvImporter\CSVExporter;
class BookGenreExporter extends CSVExporter
{
    public $file = 'books_to_genres.csv';
    protected $column_mapping = [
        ['csv_id' => 'book'],
    ];

    public function model()
    {
        return Book::with('genres')->get();
    }

    protected function generate_row($o)
    {
        $row = parent::generate_row($o);
        $heading = 'genre';
        $current = 1;
        foreach ($o->genres as $genre)
        {
            $col_name = $heading . $current;
            $current += 1;
            $row[$col_name] = $genre->csv_id;
        }
        return $row;
    }
}
