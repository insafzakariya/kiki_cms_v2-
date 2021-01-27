<?php
namespace TwiloManage\Models;

use Illuminate\Database\Eloquent\Model;

class Viewer extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'viewers';
	protected $primaryKey = 'ViewerID';
    public $timestamps = false;

    protected $fillable = [
        'Name',
        'MobileNumber'
        
       
    ];
   


    
}
