<?php
namespace ScratchCardManage\Models;

use Illuminate\Database\Eloquent\Model;

class ScratchCards extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tbl_scratch_cards';
	protected $primaryKey = 'CardID';
    public $timestamps = false;

    protected $fillable = [
        'PackageID',
        'CardType',
        'ActivityStartDate',
        'ActivityEndDate',
        'Status'
       
    ];

    public function getCodes()
    {
        return $this->hasMany('ScratchCardManage\Models\ScratchCardsCodes', 'CardID', 'CardID');   
    }
    public function getPackage()
    {
        return $this->belongsTo('ScratchCardManage\Models\Package', 'PackageID', 'PackageID');
    }

   
  


    
}
