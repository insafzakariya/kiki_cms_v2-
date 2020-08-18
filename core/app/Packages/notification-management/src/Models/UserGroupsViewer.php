<?php


namespace NotificationManage\Models;


use Illuminate\Database\Eloquent\Model;

class UserGroupsViewer extends Model
{
    protected $fillable = [
        'id',
        'user_group_id',
        'viewer_id',
        'status'
    ];

}