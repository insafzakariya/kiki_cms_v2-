<?php
namespace EpisodeManage\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tbl_episode';
	protected $primaryKey = 'episodeId';
    public $timestamps = true;

    protected $fillable = [
        'episodeName',
        'description',
        'advertisementPolicy',
        'status',
        'isTrailer',
        'episodeDesc_si',
        'episodeDesc_ta',
        'search_tag',
        'start_date',
        'end_date',
        'publish_date',
        'programId',
        'likes',
        'video_quality',
       
    ];
    public function getContentPolices()
    {
        return $this->hasMany('App\Models\ContentPolicy', 'ContentID', 'episodeId')->where('ContentType',4)->where('status',1);
    }
    public function getEpisodeThumbImages()
    {
        return $this->hasMany('App\Models\MasterImage', 'parent_id', 'episodeId')->where('parent_type','episode')->where('image_type','thumb_image')->where('status',1);   
    }
   
    public function getEpisodeChannels()
    {
        return $this->hasMany('EpisodeManage\Models\EpisodeChannel', 'episode_id', 'episodeId')->where('status',1);   
    }
    public function getProgramme()
    {
        return $this->belongsTo('ProgrammeManage\Models\Programme', 'programId', 'programId');
    }

    
}
