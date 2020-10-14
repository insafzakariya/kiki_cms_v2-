<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'policies';
	protected $primaryKey = 'PolicyID';
    public $timestamps = false;

}
