<?php

namespace App\Port;

use App\BasicEnum;

abstract class CsvColumnNames extends BasicEnum
{
    // Common for Word and Meaning
    const created_at = 'created_at';
    const updated_at = 'updated_at';
    const deleted_at = 'deleted_at';
    const user_id = 'user_id';

    // Meaning
    const meaning_type_id = 'meaning_type_id';
    const root = 'root';

    // Word
    const language_id = 'language_id';
    const meaning_id = 'meaning_id';
    const comment = 'comment';
    const text = 'text';
}