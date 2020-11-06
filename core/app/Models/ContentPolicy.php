<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPolicy extends Model{

    protected $table = 'content_policies';
    protected $primaryKey = "RowID";
    public $timestamps = true;


    protected $fillable = [
        'ContentID',
        'PolicyID',
        'ContentType',
        'Status',
        'type',
    ];
    public function getPolicy()
    {
        return $this->belongsTo('App\Models\Policy', 'PolicyID', 'PolicyID');
    }

  
}
