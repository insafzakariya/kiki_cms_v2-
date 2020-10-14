<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class Moods extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'moods';
	protected $primaryKey = 'id';
    public $timestamps = false;


    public function song()
    {
        return $this->belongsToMany('SongManage\Models\Songs', 'mood_songs', 'mood_id', 'song_id');
    }


}
