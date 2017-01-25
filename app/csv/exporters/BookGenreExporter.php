<?php
use YavorIvanov\CsvImporter\CSVExporter;
class BookGenreExporter extends CSVExporter
{
    public $file = 'books_to_genres.csv';

    // The exporter reads these mappings and converts the database table columns to the
    // CSV format. The full format for the column mappings is as follows:
    //
    // ['model_property' => ['csv_column' => ['postprocessor_name' => ['param1', 'param2' ...]]]],
    //
    // It's more common to only need a few options from the column mapping.
    // In those cases you can omit what you don't need. Here are some examples of this:
    //
    //   - Mapping a model property to a csv column without preprocessing:
    //     ['model_property' => 'csv_column'],
    //   - Postprocessing without parameters (or defaults):
    //     ['model_property' => ['csv_column' => 'postprocessor_name']],
    //   - The model property name coincides with the csv column name:
    //     'csv_column',
    //   - Using multiple postprocessors with parameters:
    //     ['model_property' => ['csv_column' => [
    //                             'processor1' => ['param1', 'param2'],
    //                             'processor2' => 'single_param'
    //     ]]],
    //   - Multiple postprocessors without parameters:
    //     ['model_property' => ['csv_column' => ['processor1', 'processor2']]],
    //
    // The 'model_property' isn't restricted to just table colmuns.
    // You may invoke any function/eloquent property. For example, 'relationship()->first()->id'
    // is a perfectly valid 'model-property'.
    protected $column_mapping = [
        ['csv_id' => 'book'],
    ];

    // The exporter checks for the existence of a model() function and will call
    // it and use its results instead of selecting the whole model table (ModelName::all()).
    //
    // In this example, we want to prefetch the genres relationship, to avoid additional queries.
    public function model()
    {
        return Book::with('genres')->get();
    }

    // While column mapping are easy to define, they are inflexible when the number
    // of columns of the exported CSV is dynamically determined. Such is the case with
    // the 'books_to_genres.csv' file where the number of 'genre' columns may vary from
    // export to export.
    //
    // In cases like these, you can override the generate_row() function. It gets passed
    // a single model instance as input, and outputs a CSV row.
    //
    // NOTE: If you choose to override this function, the exporter will not process
    // the column mappings, upnless you call the parent::generate_row() function.
    // This way you can mix custom row generation logit with column mappings, or skip the
    // automatic mappnig entirely.
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
