<?php
namespace ArtistManage\Models;

use Illuminate\Database\Eloquent\Model;

class Songs extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'songs';
	protected $primaryKey = 'songId';
    public $timestamps = false;
}
