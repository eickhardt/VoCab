<?php


namespace App\Port\Import;

use App\Exceptions\ImportException;
use App\Port\CsvConstants;
use App\Port\CsvColumnNames;
use App\Port\CsvPortUtil;
use App\WordLanguage;

class CsvColumnHeaderValidator
{
    /**
     * @var int Max count of Meaning columns required for validation.
     */
    protected $max_meaning_header_columns_count;

    /**
     * @var int Max count of Word columns required for validation.
     */
    protected $max_word_header_columns_count;

    /**
     * @var int Min count of Word columns required for validation.
     */
    protected $min_word_header_columns_count;

    public function __construct()
    {
        // This way we only need to fetch these once
        $this->max_meaning_header_columns_count = CsvConstants::getMeaningMaxHeaderColumnCount();
        $this->max_word_header_columns_count    = CsvConstants::getWordMaxColumnCount();
        $this->min_word_header_columns_count    = CsvConstants::getWordMinColumnCount();
    }

    /**
     * Verify that the columns present in the header are valid and that the minimum requirements are met.
     *
     * @param $header string The first line of the CSV file to verify, containing the columns.
     * @return array Array of languages discovered in the columns ['en' => WordLanguage, ...].
     * @throws ImportException
     */
    public function validate($header)
    {
        // Extract the column names as string array
        $columns = CsvPortUtil::getCsvLineValues($header);

        $column_count = count($columns);

        // Check if there are too few columns in the header
        if ($column_count < $this->getMinHeaderColumnsCount()) {
            throw new ImportException('Too few columns in the header: ' . $column_count);
        }

        // Check if there are too many columns in the header
        if ($column_count > $this->getMaxHeaderColumnsCount()) {
            throw new ImportException('Too many columns in the header: ' . $column_count);
        }

        // Check if there are any columns that don't have a proper string name
        $columns = $this->trimAndCheckIfHeaderContainsEmptyColumnNames($columns);

        // Check if there are duplicated columns
        if ($duplicates = $this->getDuplicatedStringsInArray($columns)) {
            throw new ImportException('Column(s) duplicated: ' . implode(', ', $duplicates));
        }

        // Verify that the mandatory columns are present
        if (!$this->mandatoryHeaderColumnsArePresent($columns)) {
            throw new ImportException('Mandatory columns are missing: ' .
                                      implode(', ', CsvConstants::MANDATORY_MEANING_COLUMNS));
        }

        $column_map                          = [];
        $word_column_language_names_to_check = [];

        // Verify that the optional columns are valid and contain the required words columns
        foreach ($columns as $column) {
            if (CsvColumnNames::isValidName($column)) {

                $column_map[$column] = true;

            } else if (CsvPortUtil::isValidWordColumnName($column)) {

                $language_shortname = explode('_', $column)[0]; // "xx_text" -> "xx"

                if (!in_array($language_shortname, $word_column_language_names_to_check)) {
                    $word_column_language_names_to_check[] = $language_shortname;
                }

                $column_map[$column] = true;

            } else {
                throw new ImportException('Unknown column found: ' . $column);
            }
        }

        // If the count of languages found in the header matches the count found in database we are good
        $valid_languages                = WordLanguage::whereIn('short_name', $word_column_language_names_to_check)->get();
        $valid_language_shortname_count = $valid_languages->count();
        if ($valid_language_shortname_count < count($word_column_language_names_to_check)) {
            throw new ImportException('Invalid language header column name, i.e. "gb_text" should be "en_text"');
        }

        // Invalidate if there are not enough language columns
        if ($valid_language_shortname_count < CsvConstants::MINIMUM_NUMBER_OF_LANGUAGE_COLUMNS) {
            throw new ImportException('Minimum language count required: ' .
                                      CsvConstants::MINIMUM_NUMBER_OF_LANGUAGE_COLUMNS);
        }

        // Verify that mandatory columns are present for the languages (for now "xx_text" and "xx_comment")
        foreach ($word_column_language_names_to_check as $language_to_check) {
            if (!isset($column_map[$language_to_check . '_' . CsvColumnNames::comment])) {
                throw new ImportException('Missing the required "'
                                          . $language_to_check . '_' . CsvColumnNames::comment . '" column');
            }
            if (!isset($column_map[$language_to_check . '_' . CsvColumnNames::text])) {
                throw new ImportException('Missing the required "'
                                          . $language_to_check . '_' . CsvColumnNames::text . '" column');
            }
        }

        // Build map for easy access later
        $languages_for_import = [];
        foreach ($valid_languages as $valid_language) {
            $languages_for_import[$valid_language->short_name] = $valid_language;
        }

        return $languages_for_import;
    }

    /**
     * Check the given column names and return true if all mandatory column names are present.
     *
     * @param string[] $columns Array og strings containing the header column names.
     * @return bool Whether or not all the mandatory columns are present.
     */
    protected function mandatoryHeaderColumnsArePresent($columns)
    {
        foreach (CsvConstants::MANDATORY_MEANING_COLUMNS as $mandatory_column) {
            if (!in_array($mandatory_column, $columns)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the maximum number of columns that can be in a valid header.
     *
     * @return int
     */
    protected function getMaxHeaderColumnsCount()
    {
        return config('app.max_active_languages') * $this->max_word_header_columns_count + $this->max_meaning_header_columns_count;
    }

    /**
     * Get the minimum number of header columns. Mandatory columns, plus one language column.
     *
     * @return int
     */
    protected function getMinHeaderColumnsCount()
    {
        return count(CsvConstants::MANDATORY_MEANING_COLUMNS) + $this->min_word_header_columns_count;
    }

    /**
     * Check if there are any duplicated string in the given array.
     *
     * @param string[] $strings
     * @return string[]
     */
    protected function getDuplicatedStringsInArray($strings)
    {
        $duplicates = [];

        $values_counted = array_count_values($strings);
        foreach ($values_counted as $value_key => $value_count) {
            if ($value_count > 1) {
                $duplicates[] = $value_key;
            }
        }

        return $duplicates;
    }

    /**
     * Trim the columns by removing spaces and check if they contain empty values. Also checks for lonely EOLs, since
     * they indicate a faulty trailing delimiter.
     *
     * @param string[] $header_columns
     * @return string[] Trimmed header columns if they contained no empty values.
     * @throws ImportException
     */
    protected function trimAndCheckIfHeaderContainsEmptyColumnNames($header_columns)
    {
        foreach ($header_columns as $column) {
            $column = trim($column);
            if (empty($column) ||
                $column === '' ||
                strlen($column) == 0 ||
                $column === CsvConstants::CSV_END_OF_LINE) {

                throw new ImportException('Header has empty column names. Perhaps you have a trailing "'
                                          . CsvConstants::CSV_COLUMN_DELIMITER . '"?');
            }
        }

        return $header_columns;
    }
}