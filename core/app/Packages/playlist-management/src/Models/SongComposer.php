<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class SongComposer extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'song_composers';
	protected $primaryKey = 'id';
    public $timestamps = false;

}
