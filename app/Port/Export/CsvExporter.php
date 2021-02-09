<?php

namespace App\Port\Export;

use App\Meaning;
use App\Port\CsvConstants;
use App\Port\CsvColumnNames;
use App\Word;
use Debugbar;
use Illuminate\Support\Collection;

abstract class CsvExporter
{
    /**
     * Generate export file contents from provided meanings.
     *
     * @param Collection $meanings The Meanings to export - the related words and languages must be in the collection.
     * @param Collection $languages WordLanguages used to create the language column headers.
     * @return string The converted words and meanings of this user as CSV file content.
     */
    public static function export($meanings, $languages)
    {
        // Create the column headers
        Debugbar::startMeasure('createCsvHeaderLine', 'Time createCsvHeaderLine');
        $csv_file_contents = CsvExporter::createCsvHeaderLine($languages);
        Debugbar::stopMeasure('createCsvHeaderLine');

        // Add a line for each meaning
        Debugbar::startMeasure('createMeaningLines', 'Time createMeaningLine');
        foreach ($meanings as $meaning) {
            $csv_file_contents .= CsvExporter::createMeaningLine($meaning, $languages);
        }
        Debugbar::stopMeasure('createMeaningLines');

        return $csv_file_contents;
    }

    /**
     * Create the header line for the CSV export file which contains names of the exported columns.
     *
     * @param Collection $languages The languages to create headers for.
     * @return string The CSV file header line.
     */
    protected static function createCsvHeaderLine(Collection $languages)
    {
        $header_line = '';

        foreach (CsvConstants::getAllMeaningCsvColumnNames() as $meaning_column) {
            $header_line .= $meaning_column . CsvConstants::CSV_COLUMN_DELIMITER;
        }

        $languages_count = count($languages);
        foreach ($languages as $key => $language) {
            $header_line .= CsvExporter::createWordLanguageColumns($language->short_name);
            if ($key != $languages_count - 1) { // No delimiter on last column
                $header_line .= CsvConstants::CSV_COLUMN_DELIMITER;
            }
        }

        return $header_line . CsvConstants::CSV_END_OF_LINE;
    }

    /**
     * Convert a meaning to a CSV line.
     *
     * @param $meaning Meaning The meaning to create a string line for.
     * @param $languages Collection WordLanguage WordLanguages that are currently enabled for the user.
     * @return string The single line string for the CSV export.
     */
    protected static function createMeaningLine(Meaning $meaning, Collection $languages)
    {
        // Root
        $meaning_line = $meaning->root . CsvConstants::CSV_COLUMN_DELIMITER;

        // Meaning type
        $meaning_line .= $meaning->meaning_type_id . CsvConstants::CSV_COLUMN_DELIMITER;

        // DB timestamps
        $meaning_line .= $meaning->created_at . CsvConstants::CSV_COLUMN_DELIMITER;
        $meaning_line .= $meaning->updated_at . CsvConstants::CSV_COLUMN_DELIMITER;
        $meaning_line .= $meaning->deleted_at . CsvConstants::CSV_COLUMN_DELIMITER;

        Debugbar::startMeasure('addWordColumns', 'Add word columns for each language');
        // Add word columns for each language
        foreach ($languages as $language) {
            $word_found = false;
            foreach ($meaning->words as $word) {
                if ($word->language_id == $language->id) {
                    $meaning_line .= CsvExporter::createWordString($word);
                    $word_found   = true;
                }
            }
            if (!$word_found) {
                $meaning_line .= CsvExporter::createWordString(null); // Add delimiters if no word exists
            }
        }
        Debugbar::stopMeasure('addWordColumns');

        return $meaning_line . CsvConstants::CSV_END_OF_LINE;
    }

    /**
     * Create a string representing one word as a part of the meaning line.
     *
     * We are omitting meaning_id and language_id here because they can be inferred during import.
     *
     * @param Word | null $word The word to convert to string representation.
     * @return string Delimiter separated word values as string.
     */
    protected static function createWordString($word)
    {
        if (!$word) {
            return
                CsvConstants::CSV_COLUMN_DELIMITER .
                CsvConstants::CSV_COLUMN_DELIMITER .
                CsvConstants::CSV_COLUMN_DELIMITER .
                CsvConstants::CSV_COLUMN_DELIMITER .
                CsvConstants::CSV_COLUMN_DELIMITER;
        }

        // Mandatory info
        $word_string = $word->text . CsvConstants::CSV_COLUMN_DELIMITER;
        $word_string .= $word->comment . CsvConstants::CSV_COLUMN_DELIMITER;

        // DB timestamps
        $word_string .= $word->created_at . CsvConstants::CSV_COLUMN_DELIMITER;
        $word_string .= $word->updated_at . CsvConstants::CSV_COLUMN_DELIMITER;
        $word_string .= $word->deleted_at . CsvConstants::CSV_COLUMN_DELIMITER;

        return $word_string;
    }

    /**
     * Create a list of column names needed for a word in the given language.
     *
     * @param $short_name string Short name of the language.
     * @return string Header column names for the given language.
     */
    protected static function createWordLanguageColumns($short_name)
    {
        return
            $short_name . CsvConstants::CSV_LANGUAGE_PREFIX_GLUE . CsvColumnNames::text . CsvConstants::CSV_COLUMN_DELIMITER .
            $short_name . CsvConstants::CSV_LANGUAGE_PREFIX_GLUE . CsvColumnNames::comment . CsvConstants::CSV_COLUMN_DELIMITER .
            $short_name . CsvConstants::CSV_LANGUAGE_PREFIX_GLUE . CsvColumnNames::created_at . CsvConstants::CSV_COLUMN_DELIMITER .
            $short_name . CsvConstants::CSV_LANGUAGE_PREFIX_GLUE . CsvColumnNames::updated_at . CsvConstants::CSV_COLUMN_DELIMITER .
            $short_name . CsvConstants::CSV_LANGUAGE_PREFIX_GLUE . CsvColumnNames::deleted_at;
    }
}