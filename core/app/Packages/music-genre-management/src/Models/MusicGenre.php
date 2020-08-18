<?php
namespace MusicGenre\Models;

use Illuminate\Database\Eloquent\Model;
use SongManage\Models\Songs;

class MusicGenre extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

    protected $table = 'audio_genre';
    protected $primaryKey = 'GenreID';
    public $timestamps = false;


	protected $fillable = [
		'icon_image',
        'GenreId',
        'Name',
        'description',
        'status',
        'color'
	];

	protected $casts = [
	    'tags' => 'array'
    ];


    public function activeSongs(){
        return $this->belongsToMany(Songs::class, 'song_genres','genre_id','song_id' )->whereStatus(1);
    }


}
