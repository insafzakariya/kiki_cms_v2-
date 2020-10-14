<?php
namespace SongManage\Models;

use ArtistManage\Models\Artist;
use Illuminate\Database\Eloquent\Model;
use MoodManage\Models\Mood;
use MusicGenre\Models\MusicGenre;
use SongsCategory\Models\SongsCategory;

class Songs extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'songs';
	protected $primaryKey = 'songId';
    public $timestamps = false;


    protected $fillable = ['name', 'isbc_code', 'description', 'search_tag', 'status', 'artistId',
        'featured_artists',
        'categoryId', 'sub_categories', 'moods', 'writerId', 'musicId', 'publisherId', 'genreId',
        'song_publisher', 'project', 'product', 'line', 'release_date', 'uploaded_date',
        'end_date',
        'composerId',
        'track',
        'smilFile',
        'streamUrl',
        'stage',
        'advertisementPolicyId',
        'explicit',
        'durations',
        'song_bulk_upload_id',
        'image',
    ];

    protected $casts = [
        'search_tag' => 'array'
    ];
    public function artist()
    {
        return $this->belongsTo('SongManage\Models\Artist', 'artistId', 'artistId');
    }

    public function composer(){
        return $this->belongsTo(\SongComposerManage\Models\SongComposer::class, 'musicId', 'id');
    }

    public function category()
    {
        return $this->belongsTo('SongManage\Models\Category', 'categoryId', 'categoryId');
    }

    public function subCategory(){
        return $this->belongsTo('SongManage\Models\Category', 'sub_categories', 'categoryId');
    }

    public function genre()
    {
        return $this->belongsTo('SongManage\Models\AudioGenre', 'genreId', 'GenreID');
    }

    public function writer()
    {
        return $this->belongsTo('PlaylistManage\Models\SongWriters', 'writerId', 'writerId');
    }

    public function mood()
    {
        return $this->belongsToMany(Mood::class, 'mood_songs', 'song_id', 'mood_id');
    }

    function primaryArtists(){
        return $this->belongsToMany(Artist::class, 'song_primary_artists', 'song_id', 'artist_id')->whereType('pa');
    }

    function featuredArtists(){
        return $this->belongsToMany(Artist::class, 'song_primary_artists', 'song_id', 'artist_id')->whereType('fa');
    }

    function artists(){
        return $this->belongsToMany(Artist::class, 'song_primary_artists', 'song_id', 'artist_id');
    }

    function projects(){
        return $this->belongsToMany(\ProjectManage\Models\Project::class, 'song_projects', 'song_id', 'project_id');
    }

    function products(){
        return $this->belongsToMany(\ProductManage\Models\Product::class, 'song_products', 'song_id', 'product_id');
    }

    function genres(){
        return $this->belongsToMany(MusicGenre::class, 'song_genres', 'song_id', 'genre_id');
    }

    public function contentPolicies(){
        return $this->belongsToMany(Policy::class, 'content_policies', 'ContentId', 'PolicyId')->where('ContentType', 7);
    }

    public function publisher(){
        return $this->hasOne(SongPublisher::class, 'publisherId', 'publisherId');
    }

    public function music(){
        return $this->belongsTo(SongComposer::class, 'musicId');
    }

    /**
     * @param $ids array
     */
    public function saveContentPolicy($ids){

        $ids = $ids ? $ids : [];

        $old_ids = $this->contentPolicies()->lists('content_policies.PolicyID')->toArray();
        $old_ids = $old_ids ? $old_ids : [];
        $new_ids = array_diff($ids, $old_ids);
        $delete_ids =  array_diff($old_ids, $ids);


        if ($delete_ids)
            $this->contentPolicies()->detach($delete_ids);

        foreach ($new_ids as $id){
            $this->contentPolicies()->attach([$id => ['ContentType' => 7]]);
        }


    }

    public function artistGenreCreate($artists , $genres){


        SongPrimaryArtists::where('song_id', $this->songId)->whereType('pa')->delete();

        $arr = [];
        if($artists){
            foreach ($artists as $artist) {
                if (is_numeric($artist)) {
                    $arr [] = [
                        'song_id' => $this->songId,
                        'artist_id' => $artist,
                        'type' => 'pa'
                    ];
                }else{
                    $newArtist = Artist::create(['name' => $artist, 'status' => 1]);
                    $arr [] = [
                        'song_id' => $this->songId,
                        'artist_id' => $newArtist->artistId,
                        'type' => 'pa'
                    ];
                }
            }

            SongPrimaryArtists::insert($arr);
        }

        foreach ($arr as $artist){
            foreach ($genres as $genre){
                ArtistGenre::firstOrCreate([
                    'artist_id' => $artist['artist_id'],
                    'genre_id' => $genre,
                ]);
            }
        }

    }

    function getArtistsString(){
        if (isset($this->primaryArtists) || isset($this->featuredArtists)) {
            $artists = "";
            if($this->primaryArtists){
                foreach($this->primaryArtists as $artist){
                    $artists .= $artist->name.", ";
                }
            }
            $artists = rtrim($artists, ", ");
            if($this->featuredArtists){
                foreach($this->featuredArtists as $key => $artist){
                    if ($key == 0){
                        $artists .= "<strong> ft </strong>".$artist->name.", ";
                    }else{
                        $artists .= $artist->name.", ";
                    }
                }
            }
            return trim($artists, ", ");
        } else {
            return "-";
        }
    }

    function getGenreString(){
        if (isset($this->genres) ) {
            $genres = "";
            foreach($this->genres as $genre){
                $genres .= $genre->Name.", ";
            }
            return rtrim($genres, ", ");

        } else {
            return "-";
        }
    }

}
