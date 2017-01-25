<?php
use YavorIvanov\CsvImporter\CSVExporter;
class FormatExporter extends CSVExporter
{
    public $file = 'formats.csv';
    protected $model = 'Format';

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
        ['csv_id' => 'id'],
        ['type' => 'format'],
    ];
}
