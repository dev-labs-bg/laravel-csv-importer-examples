<?php
use YavorIvanov\CsvImporter\CSVImporter;
class FormatImporter extends CSVImporter
{
    public $file = 'formats.csv';
    protected $model = 'Format';

    // TODO <Yavor>: Add inline documentation for this.
    protected $primary_key = ['id' => 'csv_id'];

    // TODO <Yavor>: Add inline documentation for this.
    protected $cache_key = ['id' => 'csv_id'];

    protected function update($row, $o)
    {
        $o->csv_id = $row['id'];
        $o->type = $row["format"];
        $o->save();
    }

    protected function import_row($row)
    {
        return Format::create([
            'type' => $row["format"],
            'csv_id' => $row['id'],
        ]);
    }
}
