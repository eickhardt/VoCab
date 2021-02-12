<?php


namespace App\Port\Import\Parsers;


use App\Exceptions\ImportException;
use Carbon\Carbon;
use Exception;

class CellParserDateTime implements CellParser
{
    /**
     * Turn the string value of the cell into the kind of data we need for the database.
     *
     * @param $cell_content string The string content of the cell read from CSV.
     * @return Carbon | null The parsed Carbon object containing the DateTime.
     * @throws ImportException
     */
    public function parseCell($cell_content)
    {
        if (empty($cell_content))
            return null;

        $result = null;

        try {
            $result = Carbon::parse($cell_content);

        } catch (Exception $e) {

            throw new ImportException('Unable to parse timestamp.');
        }

        return $result;
    }
}