<?php
namespace ArtistManage\Models;

use Illuminate\Database\Eloquent\Model;
use SongManage\Models\SongPrimaryArtists;

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
        'artistId',
        'name',
        'description',
        'search_tag',
        'status',
        'image'
    ];
    protected $casts = [
        'search_tag' => 'array'
    ];


    function songs(){
        return $this->hasMany(Songs::class, 'artistId', 'artistId')->whereStatus(1);
    }

    function similarArtists(){
        return $this->hasMany(SimilarArtist::class, 'artist_id', 'artistId');
    }

    function songArtists(){
        return $this->hasMany(SongPrimaryArtists::class, 'artist_id', 'artistId');
    }
}
