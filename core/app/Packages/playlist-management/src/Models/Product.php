<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{

    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
