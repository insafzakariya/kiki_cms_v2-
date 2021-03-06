<?php
namespace KikiServiceManage\Models;

use Illuminate\Database\Eloquent\Model;

class KikiService extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'kiki_services';
	protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'rslt_order',
        'parent_id',
        'status',
        'url',
        'landing_url',
        'referance',
        'bridgeid',
       
    ];

    public function getService()
    {
        return $this->belongsTo('KikiServiceManage\Models\KikiService', 'parent_id', 'id');   
    }
  


    
}
