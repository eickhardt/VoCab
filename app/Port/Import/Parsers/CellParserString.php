<?php


namespace App\Port\Import\Parsers;


class CellParserString implements CellParser
{
    /**
     * Turn the string value of the field into the kind of data we need for the database.
     *
     * @param $cell_content string The string content of the cell read from CSV.
     * @return string The parsed cell content.
     */
    public function parseCell($cell_content)
    {
        return $cell_content;
    }
}