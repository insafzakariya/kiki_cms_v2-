<?php


namespace NotificationManage\Models;


use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'tbl_program';
    protected $primaryKey = 'programId';
    
    protected $fillable = [
         'programName', 'channelId', 'programType', 'chat', 'call', 'broadcast_message', 'voting_period', 'status', 'logo', 'kids', 'logo_position', 'duration', 'videoQuality', 'description', 
         'coverImage', 'introVideo', 'advertisementPolicy', 'likes', 'subtitles', 'last_updated_on', 'last_updated_by', 'start_date', 'end_date'
    ];

}