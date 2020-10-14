<?php
namespace LyricistManage\Models;

use ArtistManage\Models\Songs;
use Illuminate\Database\Eloquent\Model;

class Lyricist extends Model{
    protected $table = 'songs_writers';
    protected $primaryKey = 'writerId';
    public $timestamps = false;

    protected $fillable = [
        'writerId',
        'name',
        'status',
    ];

    function songs(){
        return $this->hasMany(Songs::class, 'writerId', 'writerId')->whereStatus(1);
    }
}
