<?php
namespace ProgrammeManage\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeChannel extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tbl_programme_channel';
	protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'programme_id',
        'channel_id',
        'order',
        'status',
        
       
    ];
 


    
}
