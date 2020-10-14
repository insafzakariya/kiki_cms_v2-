<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class SongGenres extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'song_genres';
	protected $primaryKey = 'id';
    public $timestamps = false;

}
