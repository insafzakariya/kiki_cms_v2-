<?php
namespace MoodManage\Models;

use Illuminate\Database\Eloquent\Model;
use SongManage\Models\Songs;

class Mood extends Model{
    protected $appends = [
        'songsCount'
    ];

    protected $fillable = [
        'id',
        'name',
        'description',
        'image',
        'tags',
        'status'
    ];

    public function song()
    {
        return $this->belongsToMany('SongManage\Models\Songs', 'mood_songs', 'mood_id', 'song_id');
    }

    public function activeSongs(){
        return $this->belongsToMany(Songs::class, 'mood_songs','mood_id', 'song_id' )->whereStatus(1);
    }

    public function getSongsCountAttribute()
    {
        return $this->activeSongs()->count();
    }

}
