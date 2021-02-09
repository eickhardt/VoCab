<?php

namespace App\Port\Import;

use App\Port\CsvColumnNames;
use App\Port\CsvPortUtil;

class CsvImportModelFactory
{
    /**
     * Converts a CSV file header line to a model that can be used for interpreting CSV data lines.
     *
     * Before calling this, ensure that the header CSV header line is validated.
     *
     * @param $csv_header_line string First line of the CSB file.
     * @param $languages array Languages that exist in the header.
     * @return CsvImportModel
     */
    public static function buildCsvImportModel($csv_header_line, $languages)
    {
        $model = new CsvImportModel();

        $header_field_names = CsvPortUtil::getCsvLineValues($csv_header_line);

        foreach ($header_field_names as $index => $header_field_name) {
            if (CsvColumnNames::isValidName($header_field_name)) {
                $model->addColumn($header_field_name, $index);
            } else {
                $model->addColumn($header_field_name, $index, $languages[substr($header_field_name, 0, 2)]);
            }
        }

        return $model;
    }
}