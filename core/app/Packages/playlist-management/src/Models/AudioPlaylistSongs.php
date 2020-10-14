<?php
namespace PlaylistManage\Models;

use Illuminate\Database\Eloquent\Model;

class AudioPlaylistSongs extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'audio_playlist_songs';
	protected $primaryKey = 'id';
    public $timestamps = false;

    public function song()
    {
        return $this->belongsTo(\SongManage\Models\Songs::class, 'song_id', 'songId');
    }


}
