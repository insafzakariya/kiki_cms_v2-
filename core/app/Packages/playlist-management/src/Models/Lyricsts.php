<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class Lyricsts extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'songs_writers';
	protected $primaryKey = 'writerId';
    public $timestamps = false;


}
