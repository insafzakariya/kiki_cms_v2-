<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class SongProjects extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'song_projects';
	protected $primaryKey = 'id';
    public $timestamps = false;

}
