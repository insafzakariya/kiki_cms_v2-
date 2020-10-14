<?php
namespace PlaylistManage\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'songs_categories';
	protected $primaryKey = 'categoryId';
    public $timestamps = false;



}
