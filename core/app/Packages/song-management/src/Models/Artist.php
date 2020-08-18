<?php
namespace SongManage\Models;

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

    protected $fillable = [
        'name',
        'description',
        'search_tag',
        'status',
        'image'
    ];


    function songs(){
        return $this->hasMany(Songs::class, 'artistId', 'artistId')->whereStatus(1);
    }

    function similarArtists(){
        return $this->hasMany(SimilarArtist::class, 'artist_id', 'artistId');
    }
}
