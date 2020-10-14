<?php
namespace ProjectManage\Models;

use Illuminate\Database\Eloquent\Model;
use ProductManage\Models\Product;

class Project extends Model{
    static $projectCodePrefix = 'PRJ00';

    protected $fillable = [
        'id',
        'name'
    ];

    function policies(){
        return $this->hasMany(ProjectPolicy::class);
    }

    function getProducts(){
        return $this->hasMany(Product::class)->whereStatus(1);
    }
}
