<?php


namespace App\Port\Import;


use App\Port\CsvColumnNames;
use App\Port\CsvColumn;
use App\Port\Import\Parsers\CellParserDateTime;
use App\Port\Import\Parsers\CellParserInteger;
use App\Port\Import\Parsers\CellParserString;
use App\WordLanguage;

class CsvImportModel
{
    protected $column_to_parser_map;
    protected $columns = [];

    /**
     * Should be instantiated from the factory. Default parsers defined here.
     */
    public function __construct()
    {
        $parser_string   = new CellParserString();
        $parser_int      = new CellParserInteger();
        $parser_datetime = new CellParserDateTime();

        $this->column_to_parser_map = [
            CsvColumnNames::root            => $parser_string,
            CsvColumnNames::text            => $parser_string,
            CsvColumnNames::comment         => $parser_string,
            CsvColumnNames::meaning_type_id => $parser_int,
            CsvColumnNames::created_at      => $parser_datetime,
            CsvColumnNames::updated_at      => $parser_datetime,
            CsvColumnNames::deleted_at      => $parser_datetime,
        ];
    }

    /**
     * Add interpreter for the given mandatory or optional meaning column.
     *
     * @param string $csv_column_name The raw name of the column, as it is in the CSV file.
     * @param int $index The index of the column in the CSV file header.
     * @param WordLanguage | null $language If this is a Word field: The language it belongs to.
     */
    public function addColumn($csv_column_name, $index, $language = null)
    {
        // Get the base name of the column without the language shortname prefix
        $no_prefix_column_name = '';
        if ($language != null) {
            $no_prefix_column_name = substr($csv_column_name, 3);
            $this->columns[]       = new CsvColumn(
                $csv_column_name,
                $index,
                $this->column_to_parser_map[$no_prefix_column_name],
                $no_prefix_column_name,
                $language
            );
        } else {
            $this->columns[] = new CsvColumn(
                $csv_column_name,
                $index,
                $this->column_to_parser_map[$csv_column_name],
                $csv_column_name
            );
        }
    }

    /**
     * @return CsvColumn[] The columns this model suggests should be parsed.
     */
    public function getColumns()
    {
        return $this->columns;
    }
}