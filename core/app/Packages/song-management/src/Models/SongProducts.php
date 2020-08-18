<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class SongProducts extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'song_products';
	protected $primaryKey = 'id';
    public $timestamps = false;

    public function song()
    {
        return $this->belongsTo(\SongManage\Models\Songs::class, 'song_id', 'songId');
    }

}
