<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Font Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     Insaf Zakariya <insaf.zak@gmail.com>
 * @copyright  Copyright (c) 2015, Yasith Samarawickrama
 * @version    v1.0.0
 */
class Font extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'fonts-list';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['type', 'icon', 'unicode'];

	

}
