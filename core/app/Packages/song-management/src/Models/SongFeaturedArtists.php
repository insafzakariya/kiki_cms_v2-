<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class SongFeaturedArtists extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'song_featured_artists';
	protected $primaryKey = 'id';
    public $timestamps = false;

}
