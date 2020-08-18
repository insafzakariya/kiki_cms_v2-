<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class Music extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'music';
	protected $primaryKey = 'musicId';
    public $timestamps = false;



}
