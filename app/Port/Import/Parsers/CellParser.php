<?php


namespace App\Port\Import\Parsers;


interface CellParser
{
    /**
     * Turn the string value of the cell into the kind of data we need for the database.
     *
     * @param $cell_content string The content of the cell we are parsing.
     * @return mixed
     */
    public function parseCell($cell_content);
}