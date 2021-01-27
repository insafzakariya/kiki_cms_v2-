<?php
namespace TwiloManage\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMemberChannel extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'chat_member_channel';
	protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'block',
		'status',
		'chatChannelEntity_id',
		'chatMemberEntity_id',
		
		
        
       
    ];
   


    
}
