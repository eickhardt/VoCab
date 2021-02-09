<?php

namespace App\Port\Import;


use App\Port\CsvColumnNames;
use App\Port\Import\Database\CsvImportDbEntry;
use App\Port\CsvPortUtil;

class CsvImportModelParser
{
    /**
     * @var int The number of meanings interpreted.
     */
    protected $meaning_count = 0;

    /**
     * @var int The number of words interpreted.
     */
    protected $word_count = 0;

    /**
     * @var array CsvImportDbEntry contains entities that will be added to the database.
     */
    protected $result = [];

    /**
     * @var CsvImportModel The model used to interpret the data with.
     */
    protected $model;

    public function __construct(CsvImportModel $model)
    {
        $this->model = $model;
    }

    /**
     * Parses the given line into a Meaning with Words that can be inserted into the database.
     *
     * @param string $csv_line The line to interpret into a Meaning with associated Words.
     */
    public function parseLine($csv_line)
    {
        $values = CsvPortUtil::getCsvLineValues($csv_line);

        $new_meaning     = [];
        $new_words       = [];
        $new_words_count = 0;

        foreach ($this->model->getColumns() as $column_index => $column) {
            $column_name           = $column->getName();
            $column_name_no_prefix = $column->getNoLanguagePrefixName();
            $column_language       = $column->getLanguage();
            $parser                = $column->getParser();

            $languages_seen = [];

            $parsed_value = $parser->parseCell(
                $values[$column->getIndex()]
            );

            if ($parsed_value != null && $column_language != null) { // Word column
                $new_words[$column_language->short_name][$column_name_no_prefix] = $parsed_value;

                // Count the words for user message
                if (!isset($languages_seen[$column_language->id])) {
                    $new_words_count++;
                    $languages_seen[$column_language->id]                                 = true;
                    $new_words[$column_language->short_name][CsvColumnNames::language_id] = $column_language->id;
                }
            } else if ($parsed_value != null) { // Meaning column
                $new_meaning[$column->getName()] = $parsed_value;
            }
        }

        $this->result[] = new CsvImportDbEntry(
            $new_meaning, $new_words
        );

        $this->word_count += $new_words_count;
        $this->meaning_count++;
    }

    /**
     * @return int Get the number of meanings this parser has read.
     */
    public function getMeaningCount()
    {
        return $this->meaning_count;
    }

    /**
     * @return int Get the number of words this parser has read.
     */
    public function getWordCount()
    {
        return $this->word_count;
    }

    /**
     * @return array Retrieve the meanings and words that should be inserted into the database.
     */
    public function getResult()
    {
        return $this->result;
    }
}