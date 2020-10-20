<?php
namespace ProgrammeManage\Models;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tbl_program';
	protected $primaryKey = 'programId';
    public $timestamps = false;

    protected $fillable = [
        'programName',
        'description',
        'advertisementPolicy',
        'status',
        'kids',
        'programmeName_si',
        'programmeName_ta',
        'programmeDesc_si',
        'programmeDesc_ta',
        'search_tag',
        'start_date',
        'end_date',
        'subtitles',
        'likes',
        'programType',
       
    ];
    public function getContentPolices()
    {
        return $this->hasMany('App\Models\ContentPolicy', 'ContentID', 'channelId')->where('ContentType',1)->where('status',1);
    }


    
}