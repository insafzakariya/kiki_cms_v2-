<?php


namespace NotificationManage\Models;


use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'description',
        'file_name',
        'row_count',
        'status',
    ];

    public function fcmNotification(){
        return $this->hasMany(FcmNotification::class, 'user_group', 'id');
    }

    

}