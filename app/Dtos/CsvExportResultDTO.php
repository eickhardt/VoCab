<?php


namespace App\Dtos;


class CsvExportResultDTO
{
    /**
     * @var string[]
     */
    protected $columns;

    /**
     * @var string[][]
     */
    protected $rows;

    /**
     * @param string[] $columns
     * @param string[][] $rows
     */
    public function __construct(array $columns, array $rows)
    {
        $this->columns = $columns;
        $this->rows    = $rows;
    }

    /**
     * @return \string[][]
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @return string[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}