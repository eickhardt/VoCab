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
     * @param string $csv_header_line First line of the CSB file.
     * @param WordLanguage[] $languages Languages that exist in the header, keyed by shortname.
     * @return CsvImportModel
     */
    public static function buildCsvImportModel(string $csv_header_line, array $languages): CsvImportModel
    {
        $model = new CsvImportModel();

        $header_field_names = CsvPortUtil::getCsvLineValues($csv_header_line);

        foreach ($header_field_names as $index => $header_field_name) {
            if (CsvColumnNames::isValidName($header_field_name)) {
                $model->addColumn($header_field_name, $index);
            } else {
                // "01_it_text" => "it"
                $model->addColumn($header_field_name, $index, $languages[substr($header_field_name, 3, 2)]);
            }
        }

        return $model;
    }
}