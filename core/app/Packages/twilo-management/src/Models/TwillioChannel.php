<?php
namespace TwiloManage\Models;

use Illuminate\Database\Eloquent\Model;

class TwillioChannel extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'chat_channel';
	protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'account_sid',
        'create_date',
        'created_by',
        'friendly_name',
        'image_path',
        'service_sid',
        'sid',
        'status',
        'unique_name',
        'updated_date',
        
       
    ];
   


    
}
