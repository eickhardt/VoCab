<?php


namespace App\Port\Export\Services\CsvExportService;


use App\User;

interface ICsvExportService
{
    /**
     * @param User $user
     * @param string $request_fingerprint
     */
    public function export(User $user, string $request_fingerprint);
}