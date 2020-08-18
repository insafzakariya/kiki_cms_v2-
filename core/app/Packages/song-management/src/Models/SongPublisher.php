<?php


namespace SongManage\Models;


use Illuminate\Database\Eloquent\Model;

class SongPublisher extends Model
{
    protected $table = 'songs_publishers';
    protected $primaryKey = 'publisherId';
    public $timestamps = false;


    protected $fillable = [
        'publisherId',
        'name',
        'description',
        'search_tag',
        'status',
    ];

}