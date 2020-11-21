<?php
namespace ProgrammeSliderManage\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeSlider extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tbl_program_slider';
	protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'programID',
        'image_path',
        'modifiedBy',
        'displayOrder',
        'status',
        'start_date_time',
        'end_date_time',
       
    ];

    public function getProgramme()
    {
        return $this->belongsTo('ProgrammeManage\Models\Programme', 'programID', 'programId');   
    }
  


    
}
