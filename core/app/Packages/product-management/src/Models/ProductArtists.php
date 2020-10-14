<?php
namespace ProductManage\Models;

use ArtistManage\Models\Artist;
use Illuminate\Database\Eloquent\Model;
use SongsCategory\Models\SongsCategory;

class ProductArtists extends Model{

    protected $table = 'product_artists';
    protected $primaryKey = 'id';
    public $timestamps = false;


    function artist(){
        return $this->belongsTo(Artist::class, 'artist_id', 'artistId');
    }
}
