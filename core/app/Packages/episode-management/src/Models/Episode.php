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
        return $this->hasMany('App\Models\ContentPolicy', 'ContentID', 'programId')->where('ContentType',2)->where('status',1);
    }
    public function getProgrammeThumbImages()
    {
        return $this->hasMany('App\Models\MasterImage', 'parent_id', 'programId')->where('parent_type','programme')->where('image_type','thumb_image')->where('status',1);   
    }
    public function getProgrammeCoverImages()
    {
        return $this->hasMany('App\Models\MasterImage', 'parent_id', 'programId')->where('parent_type','programme')->where('image_type','cover_image')->where('status',1);   
    }
    public function getProgrammeChannels()
    {
        return $this->hasMany('ProgrammeManage\Models\ProgrammeChannel', 'programme_id', 'programId')->where('status',1);   
    }


    
}
