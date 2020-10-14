<?php


namespace NotificationManage\Models;


use Illuminate\Database\Eloquent\Model;

class FcmNotification extends Model
{
    protected $table = 'fcm_notification';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id', 'user_group', 'section', 'content_type', 'content_id', 'notification_time',
        'all_audiance', 'language', 'english_title', 'english_description', 'english_image', 
        'sinhala_title', 'sinhala_description', 'sinhala_image', 'tamil_title', 'tamil_description', 
        'tamil_image', 'status', 'created_at', 'updated_at'
    ];

    public function userGroup(){
        return $this->belongsTo(UserGroup::class, 'user_group', 'id');
    }

}