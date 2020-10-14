<?php

namespace SongComposerManage\Models;

use ArtistManage\Models\Songs;
use Illuminate\Database\Eloquent\Model;

class SongComposer extends Model
{

    protected $casts = [
        'tags' => 'array'
    ];

    protected $fillable = [
        'id',
        'name',
        'description',
        'status',
        'image'
    ];

    function songs()
    {
        return $this->hasMany(Songs::class, 'musicId')->whereStatus(1);
    }
}
