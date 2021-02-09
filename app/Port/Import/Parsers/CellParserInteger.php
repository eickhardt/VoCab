<?php


namespace App\Port\Import\Parsers;


class CellParserInteger implements CellParser
{
    /**
     * Turn the string value of the cell into the kind of data we need for the database.
     *
     * @param $cell_content int The content of the cell read from CSV.
     * @return int The parsed cell content.
     */
    public function parseCell($cell_content)
    {
        return intval($cell_content);
    }
}