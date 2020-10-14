<?php
namespace ProductManage\Models;

use ArtistManage\Models\Artist;
use Illuminate\Database\Eloquent\Model;
use SongManage\Models\Songs;
use SongsCategory\Models\SongsCategory;

class Product extends Model{
    protected $appends = [
        'songsCount'
    ];

    static $productCodePrefix = "C00";


    protected $fillable = [
        'id',
        'name',
        'type',
        'description',
        'image',
        'status',
    ];

    function projectCategory(){
        return $this->belongsTo(SongsCategory::class, 'project_category', 'categoryId');
    }

    function primaryArtist(){
        return $this->belongsTo(Artist::class, 'artist_id', 'artistId');
    }

    function artists(){
        return $this->belongsToMany(Artist::class, 'product_artists');
    }

    function activeSongs(){
        return $this->belongsToMany(Songs::class, 'song_products', 'product_id', 'song_id')->whereStatus(1);
    }

    public function getSongsCountAttribute()
    {
        return $this->activeSongs()->count();
    }

}
