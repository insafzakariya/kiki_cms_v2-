<?php
namespace PlaylistManage\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'songs_artists';
	protected $primaryKey = 'artistId';
    public $timestamps = false;


    function songs(){
        return $this->hasMany(Songs::class, 'artistId', 'artistId')->whereStatus(1);
    }

    function similarArtists(){
        return $this->hasMany(SimilarArtist::class, 'artist_id', 'artistId');
    }
}
