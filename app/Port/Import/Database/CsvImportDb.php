<?php


namespace App\Port\Import\Database;


use App\Exceptions\ImportException;
use App\Meaning;
use App\Port\CsvConstants;
use App\Port\CsvColumnNames;
use App\User;
use App\Word;
use Auth;
use DB;
use Exception;
use Log;

abstract class CsvImportDb
{
    /**
     * Bulk insert the records in the database.
     *
     * @param User $user The user to insert
     * @param CsvImportDbEntry[] $meanings_entries Array of CsvImportDbEntry.
     * @throws ImportException
     */
    public static function importEntries(User $user, array $meanings_entries)
    {
        $user_id            = $user->id;
        $meaning_table_name = (new Meaning)->getTable();
        $word_table_name    = (new Word)->getTable();

        // Each model were going to create as key value array so it can be bulk inserted
        $bulk_insert_meanings = [];
        $bulk_insert_words    = [];

        // Current highest meaning id used to infer the meaning_id foreign key for the new words
        $next_meaning_id = DB::select("SHOW TABLE STATUS LIKE '" . $meaning_table_name . "'")[0]->Auto_increment;

        // Each meaning has word(s) associated
        foreach ($meanings_entries as $meaning_entry) {

            // Create meaning
            $new_meaning = $meaning_entry->getNewMeaningToCreate();
            $new_meaning = self::createMandatoryFields($new_meaning, $user_id);

            $bulk_insert_meanings[] = $new_meaning;

            // Create words for this meaning
            foreach ($meaning_entry->getNewWordsToCreate() as $new_word) {
                $new_word                             = self::createMandatoryFields($new_word, $user_id);
                $new_word                             = self::createMandatoryWordFields($new_word);
                $new_word[CsvColumnNames::meaning_id] = $next_meaning_id;

                $bulk_insert_words[] = $new_word;
            }

            $next_meaning_id++;
        }

        try {
            // Create a transaction so we lock the database until the insert is complete
            DB::beginTransaction();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            self::batchInsert(
                $bulk_insert_meanings,
                $meaning_table_name,
                count($bulk_insert_meanings[0])
            );
            self::batchInsert(
                $bulk_insert_words,
                $word_table_name,
                count($bulk_insert_words[0])
            );

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            throw new ImportException("An unknown error occurred during data insertion.");
        }
    }

    /**
     * Splits the given dataset into batches and inserts them one batch at a time.
     *
     * This is used to get around a max prepared statement limit: CsvConstants::MAX_PREPARED_STATEMENT_PLACEHOLDERS.
     *
     * @param $data array The models as key => value pairs.
     * @param $table_name string Table to batch insert into.
     * @param $model_field_count int The field count of the model to insert.
     */
    protected static function batchInsert($data, $table_name, $model_field_count)
    {
        $batch_size = round(CsvConstants::MAX_PREPARED_STATEMENT_PLACEHOLDERS / ($model_field_count + 1));
        foreach (array_chunk($data, $batch_size) as $batch) {
            DB::table($table_name)->insert($batch);
        }
    }

    /**
     * The optional fields must be set in the array for the bulk insert to work - even if they are null.
     *
     * Created a function for this because the code would be duplicated otherwise.
     *
     * @param $new_model array Meaning or Word to set the fields for.
     * @param $user_id int The user id to assign.
     * @return array The modified model.
     */
    protected static function createMandatoryFields($new_model, $user_id)
    {
        $new_model[CsvColumnNames::user_id] = $user_id;

        foreach (CsvConstants::ALL_DATETIME_COLUMNS as $column) {
            if (!isset($new_model[$column])) {
                $new_model[$column] = null;
            }
        }

        return $new_model;
    }

    /**
     * The optional fields must be set in the array for the bulk insert to work - even if they are null.
     *
     * Created a function for this because the code would be duplicated otherwise.
     *
     * @param $new_word array Word to set the fields for.
     * @return array The modified model.
     */
    protected static function createMandatoryWordFields($new_word)
    {
        if (!isset($new_word[CsvColumnNames::comment])) {
            $new_word[CsvColumnNames::comment] = null;
        }

        return $new_word;
    }
}