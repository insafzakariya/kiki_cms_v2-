<?php
namespace SongManage\Models;

use ArtistManage\Models\Songs;
use Illuminate\Database\Eloquent\Model;

class SongPrimaryArtists extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'song_primary_artists';
	protected $primaryKey = 'id';
    public $timestamps = false;

    function songs(){
        return $this->belongsTo(Songs::class, 'song_id', 'songId')->whereStatus(1);
    }

}
