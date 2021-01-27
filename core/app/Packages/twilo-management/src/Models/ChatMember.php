<?php
namespace TwiloManage\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMember extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'chat_member';
	protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'create_date',
		'created_by',
		'identity',
		'image_path',
		'name',
		'status',
		'updated_date',
		'chatRoleEntity_id',
		'viewerId',
		'colour',
		'active',
		
        
       
    ];
   


    
}
