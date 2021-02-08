<?php
namespace TwiloManage\Models;

use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'chat_log';
	protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'channel_sid',
		'channel_id',
		'log'
	
	];
	
	


    
}
