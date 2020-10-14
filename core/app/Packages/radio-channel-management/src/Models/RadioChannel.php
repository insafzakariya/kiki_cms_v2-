<?php
namespace RadioChannel\Models;

use Illuminate\Database\Eloquent\Model;

class RadioChannel extends Model{
    public $timestamps = false;

    protected $fillable = [
      'image'
    ];
}
