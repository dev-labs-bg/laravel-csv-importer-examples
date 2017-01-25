<?php
use YavorIvanov\CsvImporter\CSVImporter;
class GenreImporter extends CSVImporter
{
    public $file = 'genres.csv';
    protected $model = 'Genre';

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

    protected function update($row, $o)
    {
        $o->csv_id = $row['id'];
        $o->name = $row["name"];
        $o->save();
    }

    protected function import_row($row)
    {
        return Genre::create([
            'name' => $row["name"],
            'csv_id' => $row['id'],
        ]);
    }
}
