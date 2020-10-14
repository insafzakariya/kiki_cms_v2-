<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class MoodSongs extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mood_songs';
	protected $primaryKey = 'id';
    public $timestamps = false;

    public function mood()
    {
        return $this->belongsTo('SongManage\Models\Moods');
    }

    public function song()
    {
        return $this->belongsTo('SongManage\Models\Songs');
    }

}
