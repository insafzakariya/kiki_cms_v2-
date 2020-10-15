<?php
namespace ChannelManage\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tbl_channels';
	protected $primaryKey = 'channelId';
    public $timestamps = false;

    protected $fillable = [
        'channelName',
        'channelDesc',
        'workingHours',
        'logoImage',
        'introVideo',
        'advertisementPolicy',
        'status',
        'channel_order',
        'kids',
        'parentChannelId',
        'channelName_si',
        'channelName_ta',
        'channelDesc_si',
        'channelDesc_ta',
        'search_tag'
       
    ];
    public function getContentPolices()
    {
        return $this->hasMany('App\Models\ContentPolicy', 'ContentID', 'channelId')->where('ContentType',2);
    }


    
}
