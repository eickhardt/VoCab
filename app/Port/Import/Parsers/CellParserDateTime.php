<?php


namespace App\Port\Import\Parsers;


use Carbon\Carbon;

class CellParserDateTime implements CellParser
{
    /**
     * Turn the string value of the cell into the kind of data we need for the database.
     *
     * @param $cell_content string The string content of the cell read from CSV.
     * @return Carbon | null The parsed Carbon object containing the DateTime.
     */
    public function parseCell($cell_content)
    {
        if (empty($cell_content))
            return null;

        return Carbon::parse($cell_content);
    }
}