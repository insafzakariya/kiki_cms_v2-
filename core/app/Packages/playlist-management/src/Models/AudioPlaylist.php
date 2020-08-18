<?php
namespace PlaylistManage\Models;

use App\Models\Policy;
use Illuminate\Database\Eloquent\Model;
use SongManage\Models\Songs;

class AudioPlaylist extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'audio_playlist';
	protected $primaryKey = 'id';

    protected $fillable = ['name', 'status', 'publish_date', 'playlist_type', 'description', 'content_policy',
                           'advertisement_policy', 'release_date', 'type_name','expiry_date'];


    public function activeSongs(){
        return $this->belongsToMany(Songs::class, 'audio_playlist_songs','playlist_id', 'song_id' )->whereStatus(1);
    }

    function contentPolicies(){
        return $this->belongsToMany(Policy::class, 'playlist_policies', 'playlist_id', 'policy_id')->where("pollicy_type", 1);
    }

    function advertisementPolicies(){
        return $this->belongsToMany(Policy::class, 'playlist_policies', 'playlist_id', 'policy_id')->where("pollicy_type", 2);
    }

    function policies(){
        return $this->belongsToMany(Policy::class, 'playlist_policies', 'playlist_id', 'policy_id');
    }


}
