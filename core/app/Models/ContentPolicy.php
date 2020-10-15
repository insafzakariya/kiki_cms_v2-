<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPolicy extends Model{

    protected $table = 'content_policies';
    protected $primaryKey = "RowID";


    protected $fillable = [
        'ContentID',
        'PolicyID',
        'ContentType',
        'Status',
        'type',
    ];

  
}