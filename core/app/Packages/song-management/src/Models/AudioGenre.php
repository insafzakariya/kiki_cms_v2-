<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class AudioGenre extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'audio_genre';
	protected $primaryKey = 'GenreID';
    public $timestamps = false;


}
