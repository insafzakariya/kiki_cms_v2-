<?php


namespace NotificationManage\Models;


use Illuminate\Database\Eloquent\Model;

class Viewers extends Model
{
    protected $table = 'viewers';
    protected $primaryKey = 'ViewerID';
    
    protected $fillable = [
        'ViewerID', 'DeviceID '
    ];


}