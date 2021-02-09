<?php

namespace App\Port\Import;

use App\Exceptions\ImportException;
use App\Port\Import\Database\CsvImportDb;
use App\Port\CsvPortUtil;
use App\User;
use Exception;
use File;
use Log;

class CsvImporter
{
    /**
     * @var CsvColumnHeaderValidator Entity for validating the header of the CSV file.
     */
    protected $validator;

    /**
     * @var User The user to import for.
     */
    protected $user;

    /**
     * @var string The path to the file on local disk.
     */
    protected $file_path;

    /**
     * @var string The id of the import.
     */
    protected $import_id;

    /**
     * @param CsvColumnHeaderValidator $validator Entity for performing the header validation.
     * @param User $user The user to import for.
     * @param string $temp_file_path The path to the file on local disk (/tmp folder for uploads).
     * @param string $import_id The id of the import.
     */
    public function __construct(CsvColumnHeaderValidator $validator, User $user, $temp_file_path, $import_id)
    {
        $this->validator = $validator;
        $this->user      = $user;
        $this->import_id = $import_id;
        $this->file_path = $this->saveImportFile($temp_file_path);

        Log::info(self::class . ' constructed', [
            'user_id'        => $user->id,
            'import_id'      => $import_id,
            'file_path'      => $this->file_path,
            'temp_file_path' => $temp_file_path
        ]);
    }

    /**
     * Attempt to validate and import the given CSV file into the database.
     *
     * @return string Message to show the user if successful.
     * @throws ImportException
     * @throws Exception
     */
    public function import()
    {
        Log::info(self::class . ' starting import', [
            'user_id'   => $this->user->id,
            'import_id' => $this->import_id,
            'file_path' => $this->file_path
        ]);

        $model  = null;
        $parser = null;

        $file_handle = $this->getFileHandle($this->file_path);

        $line_number = 0;
        while (($line = fgets($file_handle)) !== false) { // Read one line of the file at a time

            $line_number++;

            // First line is the header which we want to validate and use to build a model for parsing
            if ($line_number == 1) {

                // Validate the columns, and get the languages in use in the file
                $languages = $this->validator->validate($line);

                // Build a model from the headers that we can use to interpret the data with
                $model  = CsvImportModelFactory::buildCsvImportModel($line, $languages);
                $parser = new CsvImportModelParser($model);

            } else {
                $parser->parseLine($line);
            }
        }

        if ($line_number <= 2) {
            throw new ImportException('The CSV file must contain at least 1 header line and 1 meaning line');
        }

        fclose($file_handle);

        // All lines have been read successfully, commit the result to DB
        CsvImportDb::importEntries($this->user, $parser->getResult());

        return "Import complete: " . $parser->getMeaningCount()
               . " meanings and " . $parser->getWordCount() . " words imported.";
    }

    /**
     * Once processing has completed, call this to delete the CSV file from storage.
     */
    public function deleteLocalCsvFile()
    {
        File::delete($this->file_path);
    }

    /**
     * Get the temp file upload and store it so we can access it from the import job later.
     *
     * @param string $temp_file_path The path to the file on local disk (/tmp folder for uploads).
     * @return string Path to the file we saved in storage.
     */
    protected function saveImportFile($temp_file_path)
    {
        $new_file_name     = CsvPortUtil::generateCsvImportFileName($this->user->id, $this->import_id);
        $file_storage_path = CsvPortUtil::getCsvImportFilePath($new_file_name);

        File::copy($temp_file_path, $file_storage_path);

        return $file_storage_path;
    }

    /**
     * Get file handle we can use to read the CSV file line by line, saving us the RAM it would take reading it all.
     *
     * @param string $file_path Absolute path to the CSV file we want to read.
     * @return resource
     * @throws Exception
     */
    protected function getFileHandle($file_path)
    {
        $handle = fopen($file_path, 'r');
        if ($handle) {
            return $handle;
        }

        throw new Exception("Could not open CSV file.");
    }
}