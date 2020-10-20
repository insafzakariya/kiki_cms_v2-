<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterImage extends Model{

    protected $table = 'tbl_master_image';
    protected $primaryKey = "id";


    protected $fillable = [
        'parent_type',
        'parent_id',
        'image_type',
        'file_name',
        
    ];

    
}
