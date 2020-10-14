<?php
namespace PlaylistManage\Models;

use Illuminate\Database\Eloquent\Model;

class SongWriters extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'songs_writers';
	protected $primaryKey = 'id';
    public $timestamps = false;

}
