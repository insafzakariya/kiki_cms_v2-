<?php
namespace ScratchCardManage\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'packages';
	protected $primaryKey = 'PackageID';
    public $timestamps = false;

    protected $fillable = [
        'Description',
        'Status'
       
    ];

   
  


    
}
