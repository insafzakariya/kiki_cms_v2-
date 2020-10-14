<?php

namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistGenre extends Model
{
    protected $fillable = [
        'artist_id',
        'genre_id',
    ];
}
