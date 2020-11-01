<?php
namespace EpisodeManage\Models;

use Illuminate\Database\Eloquent\Model;

class EpisodeChannel extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tbl_episode_channel';
	protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'episode_id',
        'channel_id',
        'order',
        'status',
        
       
    ];
    public function getChannel()
    {
        return $this->belongsTo('ChannelManage\Models\Channel', 'channel_id', 'channelId');
    }
    public function getEpisode()
    {
        return $this->belongsTo('EpisodeManage\Models\Episode', 'episode_id', 'episodeId');
    }


    
}
