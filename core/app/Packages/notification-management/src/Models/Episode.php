<?php


namespace NotificationManage\Models;


use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{

    protected $table = 'tbl_episode';
    protected $primaryKey = 'episodeId';
    
    protected $fillable = [
        'episodeId', 'episodeName', 'programId', 'file_name', 'liveUrl', 'intro', 'description', 'start_date', 'end_date', 'advertisement_policy', 'status'
        ,'streamUrl', 'kids', 'smilFile', 'last_updated_on', 'last_updated_by', 'publish_date', 'durations', 'isTrailer' ];

}