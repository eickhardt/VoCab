<?php


namespace App\Port\Export\Services\CsvExportDataProcessorService;


use App\Dtos\CsvExportResultDTO;
use Illuminate\Support\Collection;

interface ICsvExportDataProcessorService
{
    /**
     * Generate export file contents from provided meanings.
     *
     * @param Collection $meanings The Meanings to export - the related words must be in the collection.
     * @return CsvExportResultDTO The name of the created CSN file.
     */
    public function process(Collection $meanings): CsvExportResultDTO;
}