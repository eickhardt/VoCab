<?php


namespace App\Port;


abstract class CsvConstants
{
    /**
     * @var string The default delimiter of the CSV fields.
     */
    const CSV_COLUMN_DELIMITER = ';';

    /**
     * @var string The char we put between the language field name prefix and the language field names.
     */
    const CSV_FIELD_GLUE = '_';

    /**
     * @var string Name of the folder in storage where export CSV files are kept.
     */
    const CSV_EXPORT_FOLDER = 'csv-export/';

    /**
     * @var string Name of the folder in storage where import CSV files are kept.
     */
    const CSV_IMPORT_FOLDER = 'csv-import/';

    /**
     * @var string The EOL character.
     */
    const CSV_END_OF_LINE = PHP_EOL;

    /**
     * @var integer The minimum number of language fields needed for validation.
     */
    const MINIMUM_NUMBER_OF_LANGUAGE_COLUMNS = 1;

    /**
     * All possible datetime field names.
     */
    const ALL_DATETIME_COLUMNS = [CsvColumnNames::created_at, CsvColumnNames::updated_at, CsvColumnNames::deleted_at];

    /**
     * @var array Names of Word datetime fields.
     */
    const WORD_DATETIME_COLUMNS = self::ALL_DATETIME_COLUMNS;

    /**
     * @var array Names of Meaning datetime fields.
     */
    const MEANING_DATETIME_COLUMNS = self::ALL_DATETIME_COLUMNS;

    /**
     * @var array Names of the Meaning fields that are mandatory.
     */
    const MANDATORY_MEANING_COLUMNS = [CsvColumnNames::root, CsvColumnNames::meaning_type_id];

    /**
     * @var array Names of optional Meaning fields.
     */
    const OPTIONAL_MEANING_COLUMNS = self::MEANING_DATETIME_COLUMNS;

    /**
     * The length of a language shortname string.
     */
    const LANGUAGE_SHORTNAME_LENGTH = 2;

    /**
     * @var array Names of the Word fields that are mandatory.
     */
    const MANDATORY_WORD_COLUMNS = [CsvColumnNames::text];

    /**
     * @var array Names of optional Word fields.
     */
    const OPTIONAL_WORD_COLUMNS = [
        CsvColumnNames::comment,
        CsvColumnNames::created_at,
        CsvColumnNames::updated_at,
        CsvColumnNames::deleted_at
    ];

    /**
     * See: https://stackoverflow.com/questions/18100782/import-of-50k-records-in-mysql-gives-general-error-1390-prepared-statement-con
     */
    const MAX_PREPARED_STATEMENT_PLACEHOLDERS = 65536;

    /**
     * @return array Returns array of all meaning column names, mandatory and optional.
     */
    public static function getAllMeaningCsvColumnNames()
    {
        return array_merge(self::MANDATORY_MEANING_COLUMNS, self::OPTIONAL_MEANING_COLUMNS);
    }

    /**
     * @return integer Get the maximum number of header columns for a meaning.
     */
    public static function getMeaningMaxHeaderColumnCount()
    {
        return count(self::getAllMeaningCsvColumnNames());
    }

    /**
     * @return array Returns array of all word column names, mandatory and optional.
     */
    public static function getAllWordCsvColumnNames()
    {
        return array_merge(self::MANDATORY_WORD_COLUMNS, self::OPTIONAL_WORD_COLUMNS);
    }

    /**
     * @return integer Get the maximum number of header columns for a meaning.
     */
    public static function getWordMaxColumnCount()
    {
        return count(self::getAllWordCsvColumnNames());
    }

    /**
     * @return integer Get the maximum number of header columns for a meaning.
     */
    public static function getWordMinColumnCount()
    {
        return count(self::MANDATORY_WORD_COLUMNS);
    }
}