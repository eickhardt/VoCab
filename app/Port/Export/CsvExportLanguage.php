<?php


namespace App\Port\Export;


class CsvExportLanguage
{
    /**
     * @var int $highest_word_count The highest number of words in this language found in the meanings to export.
     */
    public $highest_word_count = 0;

    /**
     * @var string $language_shortname Short name of the language.
     */
    public $language_shortname;

    /**
     * @var int $language_id Database id of the language.
     */
    public $language_id;
}