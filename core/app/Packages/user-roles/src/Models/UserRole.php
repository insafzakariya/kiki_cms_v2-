<?php
namespace UserRoles\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * UserRole Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     Insaf Zakariya <insaf.zak@gmail.com>
 * @copyright  Copyright (c) 2016, Insaf Zakariya
 * @version    v1.0.0
 */
class UserRole extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'slug', 'permissions', 'visible','created_by', 'created_at', 'updated_at'];

}
