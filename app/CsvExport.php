<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CsvExport
 *
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|CsvExport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CsvExport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CsvExport query()
 * @mixin \Eloquent
 */
class CsvExport extends Model
{
    /**
     * Fillable fields for a CsvExport.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'file_name', 'file_exists',
        'created_at', 'updated_at', 'user_id'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'csv_exports';
}
