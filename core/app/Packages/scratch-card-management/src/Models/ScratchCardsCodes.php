<?php
namespace ScratchCardManage\Models;

use Illuminate\Database\Eloquent\Model;

class ScratchCardsCodes extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tbl_scratch_card_codes';
	protected $primaryKey = 'RecordID';
    public $timestamps = false;

    protected $fillable = [
        'CardID',
        'CardCode',
        'CardStatus'
       
    ];

   
  


    
}
