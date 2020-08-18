<?php


namespace SongManage\Models;


use Illuminate\Database\Eloquent\Model;

class SongBulkUpload extends Model
{
    protected $fillable = [
        'id',
        'date',
        'user_id',
        'row_count',
        'file_name',
        'start',
        'end',
    ];

}