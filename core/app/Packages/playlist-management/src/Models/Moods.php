<?php
namespace PlaylistManage\Models;

use Illuminate\Database\Eloquent\Model;

class Moods extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'moods';
	protected $primaryKey = 'id';
    public $timestamps = false;



}
