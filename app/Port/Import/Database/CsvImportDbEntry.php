<?php


namespace App\Port\Import\Database;


class CsvImportDbEntry
{
    protected $new_meaning_to_create;
    protected $new_words_to_create = [];

    public function __construct(array $new_meaning_to_create, array $new_words_to_create)
    {
        $this->new_meaning_to_create = $new_meaning_to_create;
        $this->new_words_to_create   = $new_words_to_create;
    }

    public function getNewMeaningToCreate()
    {
        return $this->new_meaning_to_create;
    }

    public function getNewWordsToCreate()
    {
        return $this->new_words_to_create;
    }
}