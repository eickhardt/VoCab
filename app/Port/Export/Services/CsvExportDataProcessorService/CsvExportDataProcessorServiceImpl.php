<?php

namespace App\Port\Export\Services\CsvExportDataProcessorService;

use App\Dtos\CsvExportResultDTO;
use App\Meaning;
use App\Port\CsvConstants;
use App\Port\Export\CsvExportLanguage;
use App\Word;
use App\WordLanguage;
use Illuminate\Support\Collection;

class CsvExportDataProcessorServiceImpl implements ICsvExportDataProcessorService
{
    /**
     * @var CsvExportLanguage[] Map of language_id => information about the languages we are exporting. For performance.
     */
    protected $export_languages = [];

    /**
     * Generate export file contents from provided meanings.
     *
     * @param Collection $meanings The Meanings to export - the related words must be in the collection.
     * @return CsvExportResultDTO The result generated.
     */
    public function process(Collection $meanings): CsvExportResultDTO
    {
        $this->determineLanguages($meanings);

        $columns = $this->buildColumns();
        $rows    = $this->buildRows($meanings);

        return new CsvExportResultDTO($columns, $rows);
    }

    /**
     * Create the columns for the CSV export file.
     *
     * @return string[] The CSV file header columns.
     */
    protected function buildColumns(): array
    {
        $header_columns = [];

        // Create columns for the meaning
        foreach (CsvConstants::getAllMeaningCsvColumnNames() as $meaning_column) {
            $header_columns[] = $meaning_column;
        }

        // Create the columns for the languages / words (there may be several words in the same language)
        foreach ($this->export_languages as $export_language) {
            $language_short_name = $export_language->language_shortname;
            $word_count          = $export_language->highest_word_count;

            for ($index = 1; $index <= $word_count; $index++) {
                $header_columns = array_merge($header_columns, $this->buildWordLanguageColumns($language_short_name, $index));
            }
        }

        return $header_columns;
    }

    /**
     * Create a list of column names needed for a word in the given language.
     *
     * @param string $short_name Short name of the language.
     * @param int $index The number word in this language i.e. "1_en_text, 2_en_text".
     * @return string[] Header column names for the given language.
     */
    protected function buildWordLanguageColumns(string $short_name, int $index): array
    {
        $word_columns = CsvConstants::getAllWordCsvColumnNames();
        $prefix       = sprintf("%02d", $index); // Ensure minimum of 2 digits like 01, 05, 09, 99.

        $columns = [];

        foreach ($word_columns as $column) {
            $columns[] = $prefix . CsvConstants::CSV_FIELD_GLUE . $short_name . CsvConstants::CSV_FIELD_GLUE . $column;
        }

        return $columns;
    }

    /**
     * Generate a row for each meaning in the given collection.
     *
     * @param Collection $meanings Meanings to generate rows for.
     * @return string[][]
     */
    protected function buildRows(Collection $meanings): array
    {
        $rows = [];

        foreach ($meanings as $meaning) {
            $rows[] = $this->buildRow($meaning);
        }

        return $rows;
    }

    /**
     * Convert a meaning to a CSV line.
     *
     * @param $meaning Meaning The meaning to create a string line for.
     * @return string[] The single line string for the CSV export.
     */
    protected function buildRow(Meaning $meaning): array
    {
        return array_merge(
            $this->buildMeaningCells($meaning),
            $this->buildWordsCells($meaning)
        );
    }

    /**
     * Create array of cells with values for the meaning.
     *
     * @param Meaning $meaning
     * @return string[]
     */
    protected function buildMeaningCells(Meaning $meaning): array
    {
        $meaning_line = [];

        $columns = CsvConstants::getAllMeaningCsvColumnNames();

        foreach ($columns as $column) {
            $meaning_line[] = $meaning->{$column};
        }

        return $meaning_line;
    }

    /**
     * Create the words part of a meaning CSV data line.
     *
     * @param Meaning $meaning
     * @return string[]
     */
    protected function buildWordsCells(Meaning $meaning): array
    {
        $words = [];

        // Add a set of word cells for each language
        foreach ($this->export_languages as $export_language) {
            $words_added_count = 0;

            // Loop over all the words of this meaning and add the ones that are in the current language
            foreach ($meaning->words as $word) {

                // If we find a word in the given language, add it
                if ($word->language_id == $export_language->language_id) {
                    $words_added_count++;
                    $words = array_merge($words, $this->buildWordCells($word));
                }
            }

            // Pad with empty cells if there are not as many words on this meaning as on the one with the most
            // Ensures that we put the right values in the right cells and that rows have equal length
            $empty_word_count = $export_language->highest_word_count - $words_added_count;
            if ($empty_word_count > 0) {
                for ($index = 1; $index <= $empty_word_count; $index++) {
                    $words = array_merge($words, $this->buildEmptyWordCells());
                }
            }
        }

        return $words;
    }

    /**
     * Create cells with values representing one word.
     *
     * We are omitting "meaning_id" and "language_id" since they can be inferred during import.
     *
     * @param Word $word The word to convert to array value representation.
     * @return string[] Array of word values.
     */
    protected function buildWordCells(Word $word): array
    {
        $columns = CsvConstants::getAllWordCsvColumnNames();

        $cells = [];
        foreach ($columns as $column) {
            $cells[] = $word->{$column};
        }

        return $cells;
    }

    /**
     * @return string[] A a number of empty cells corresponding to the word columns.
     */
    protected function buildEmptyWordCells(): array
    {
        $cells = [];

        for ($index = 1; $index <= CsvConstants::getWordMaxColumnCount(); $index++) {
            $cells[] = '';
        }

        return $cells;
    }

    /**
     * Creates a map of [language_id => CsvExportLanguage] and stores in on this object.
     *
     * @param Collection $meanings The users export data.
     */
    protected function determineLanguages(Collection $meanings)
    {
        foreach ($meanings as $meaning) {

            // Count how many words there are in each language belonging to this meaning
            $language_word_counter = [];
            foreach ($meaning->words as $word) {
                if (!isset($language_word_counter[$word->language_id])) {
                    $language_word_counter[$word->language_id] = 1;
                } else {
                    $language_word_counter[$word->language_id]++;
                }
            }

            // Word count in hand, update the highest count we have registered for each language
            foreach ($language_word_counter as $language_id => $word_count) {

                if (!isset($this->export_languages[$language_id])) {
                    // If the language has not yet been seen, create it

                    $export_language                     = new CsvExportLanguage();
                    $export_language->highest_word_count = $word_count;
                    $export_language->language_id        = $language_id;

                    $this->export_languages[$language_id] = $export_language;

                } else if ($this->export_languages[$language_id]->highest_word_count < $word_count) {
                    // The language already exists, but we have a higher word count, update

                    $this->export_languages[$language_id]->highest_word_count = $word_count;
                }
            }
        }

        // Gather the language ids we have found and use them to look up the shortnames
        $language_ids = [];
        foreach ($this->export_languages as $language_id => $export_language) {
            $language_ids[] = $language_id;
        }

        $language_ids_to_short_names_map = WordLanguage::getLanguageIdToShortNameMap($language_ids);

        foreach ($language_ids_to_short_names_map as $language_id => $language_shortname) {
            $this->export_languages[$language_id]->language_shortname = $language_shortname;
        }
    }
}