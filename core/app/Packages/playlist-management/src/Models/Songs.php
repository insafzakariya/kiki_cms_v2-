<?php
namespace PlaylistManage\Models;

use Illuminate\Database\Eloquent\Model;

class Songs extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'songs';
	protected $primaryKey = 'songId';
    public $timestamps = false;

    protected $fillable = ['name', 'isbc_code', 'description', 'search_tag', 'status', 'artistId', 'featured_artists',
                           'categoryId', 'sub_categories', 'moods', 'writerId', 'musicId', 'publisherId', 'genreId',
                           'song_publisher', 'project', 'product', 'line', 'release_date', 'uploaded_date', 'end_date', 'composerId'];


    public function artist()
    {
        return $this->belongsTo('PlaylistManage\Models\Artist', 'artistId', 'artistId');
    }

    public function category()
    {
        return $this->belongsTo('PlaylistManage\Models\Category', 'categoryId', 'categoryId');
    }

    public function genre()
    {
        return $this->belongsTo('PlaylistManage\Models\AudioGenre', 'genreId', 'GenreID');
    }

    public function writer()
    {
        return $this->belongsTo('PlaylistManage\Models\SongWriters', 'writerId', 'writerId');
    }

}
