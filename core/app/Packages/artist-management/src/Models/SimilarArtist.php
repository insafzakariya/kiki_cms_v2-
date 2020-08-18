<?php
namespace ArtistManage\Models;

use Illuminate\Database\Eloquent\Model;

class SimilarArtist extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'similar_artists';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    public $fillable = ['artist_id', 'similar_artist_id'];


    function artist(){
        return $this->hasOne(Artist::class, 'artistId', 'similar_artist_id')->whereStatus(1);
    }
}
