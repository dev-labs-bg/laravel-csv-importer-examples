<?php
use YavorIvanov\CsvImporter\CSVImporter;
class GenreImporter extends CSVImporter
{
    public $file = 'genres.csv';
    protected $model = 'Genre';

    // TODO <Yavor>: Add inline documentation for this.
    protected $primary_key = ['id' => 'csv_id'];

    // TODO <Yavor>: Add inline documentation for this.
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
