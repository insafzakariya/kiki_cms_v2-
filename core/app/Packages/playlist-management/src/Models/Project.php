<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;
use ProductManage\Models\Product;

class Project extends Model{

    protected $table = 'projects';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
