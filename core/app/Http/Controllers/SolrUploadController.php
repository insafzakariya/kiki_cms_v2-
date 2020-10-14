<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 0);
use App\Http\Controllers\Controller;
use ArtistManage\Models\Artist;
use Exception;
use Illuminate\Http\Request;
use Log;
use PlaylistManage\Models\AudioPlaylist;
use ProductManage\Models\Product;
use SongManage\Models\Songs;

class SolrUploadController extends Controller
{

    private $solrController;
    private $limit;

    public function __construct(SolrController $solrController)
    {
        $this->solrController = $solrController;
        $this->limit = env("Solr_Limit", 1000);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *  Remove all solr document
     * @return string
     */
    public function removeAllSolr()
    {
        try {
            $this->solrController->delete_all_music_client();
            return "All records are deleted";
        } catch (Exception $exception) {
            Log::error("solr remove all error " . $exception->getMessage());
        }
        //  $this->solrController->;
    }

    /**
     * refresh all songs solr documents
     * @return string
     */
    public function allSongs()
    {
        try {
            $songs = Songs::with([
                'projects', 'products',
                'writer', 'composer', 'publisher', 'subCategory',
            ])->take($this->limit)->get();
            $this->solrController->delete_all_song();
            foreach ($songs as $song) {
                $this->songSolr($song);
            }
            return $songs->count() . " songs records successfully updated";
        } catch (Exception $exception) {
            Log::error("song solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * refresh song document by ID
     * @param $id
     * @return string
     */
    public function song($id)
    {
        try {
            $song = Songs::with([
                'projects', 'products',
                'writer', 'composer', 'publisher', 'subCategory',
            ])
                ->where('songId', $id)
                ->first();

            if ($song) {
                $this->solrController->kiki_song_delete_by_id($song->songId);
                $this->songSolr($song);
                return "Song ID -$id record successfully updated";
            } else {
                return "Song ID -$id not found. Please recheck ID";
            }
        } catch (Exception $exception) {
            Log::error("single song solr bulk upload error " . $exception->getMessage());
        }
    }

    public function songDeleteAll()
    {
        try {
            $this->solrController->delete_all_song();
            return "All songs record are deleted successfully";
        } catch (Exception $exception) {
            Log::error("single song solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * Song solr document delete by ID
     * @param $id
     * @return string
     */
    public function songDelete($id)
    {
        try {
            $song = Songs::where('songId', $id)
                ->first();
            if ($song) {
                $this->solrController->kiki_song_delete_by_id($song->songId);
                return "song ID -$id record successfully Deleted";
            }
        } catch (Exception $exception) {
            Log::error("single song solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     *  refresh artist document
     * @return string
     */
    public function allArtist()
    {

        try {
            $artists = Artist::take($this->limit)->get();
            //$artists = Artist::take(10)->get();
            $this->solrController->delete_all_artist();
            foreach ($artists as $artist) {
                //need to create a function
                //$this->solrController->kiki_artist_delete_by_id($artist->artistId);
                $this->artistSolr($artist);
            }
            return $artists->count() . "  artists records successfully updated";
        } catch (Exception $exception) {
            Log::error("artist solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * refresh artist document by ID
     * @param $id
     * @return string
     */
    public function artist($id)
    {
        try {
            $artist = Artist::find($id);
            if ($artist) {
                $this->solrController->kiki_artist_delete_by_id($id);
                $this->artistSolr($artist);
                return "Artist ID -$id record successfully updated";
            } else {
                return "Artist ID -$id not found. Please recheck ID";
            }
        } catch (Exception $exception) {
            Log::error("single artist solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * delete all artist document
     * @return string
     */
    public function artistDeleteAll()
    {
        try {
            $this->solrController->delete_all_artist();
            return "All artists record are deleted successfully";
        } catch (Exception $exception) {
            Log::error("single artist solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * Artist solr document delete by ID
     * @param $id
     * @return string
     */
    public function artistDelete($id)
    {
        try {
            $artist = Artist::find($id);
            if ($artist) {
                $this->solrController->kiki_artist_delete_by_id($id);
                return "Artist ID -$id record successfully Deleted";
            } else {
                return "Artist ID -$id not found. Please recheck ID";
            }
        } catch (Exception $exception) {
            Log::error("single artist solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * refresh playlist document
     * @return string
     */
    public function allPlaylist()
    {
        try {
            $playlists = AudioPlaylist::where('playlist_type', 'g')->take($this->limit)->get();
            $this->solrController->delete_all_playlist();
            foreach ($playlists as $playlist) {
                $this->playListSolr($playlist);
            }
            return $playlists->count() . "  playlists records successfully updated";
        } catch (Exception $exception) {
            Log::error("song solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * refresh playlist document by ID
     * @param $id
     * @return string
     */
    public function playlist($id)
    {
        try {
            $playList = AudioPlaylist::where('playlist_type', 'g')->find($id);
            if ($playList) {
                $this->solrController->kiki_playlist_delete_by_id($id);
                $this->playListSolr($playList);
                return "Playlist ID -$id record successfully updated";
            } else {
                return "Playlist ID -$id not found. Please recheck ID";
            }
        } catch (Exception $exception) {
            Log::error("single playlist solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     *
     * @return string
     */
    public function playlistDeleteAll()
    {
        try {
            $this->solrController->delete_all_playlist();
            return "All playlists record are deleted successfully";
        } catch (Exception $exception) {
            Log::error("single playlist solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * playlist solr document delete by ID
     * @param $id
     * @return string
     */
    public function playlistDelete($id)
    {
        try {
            $playList = AudioPlaylist::where('playlist_type', 'g')->find($id);
            if ($playList) {
                $this->solrController->kiki_playlist_delete_by_id($id);
                return "Playlist ID -$id record successfully Deleted";
            } else {
                return "Playlist ID -$id not found. Please recheck ID";
            }
        } catch (Exception $exception) {
            Log::error("single playlist solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * refresh album document
     * @return string
     */
    public function allAlbum()
    {
        try {
            $products = Product::with('projectCategory')
                ->where('type', 'Album')
                ->take($this->limit)
                ->get();
            $this->solrController->delete_all_product();
            foreach ($products as $product) {
                $this->productSolr($product);
            }
            return $products->count() . "  albums records successfully updated";
        } catch (Exception $exception) {
            Log::error("Album solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * refresh album document by ID
     * @param $id
     * @return string
     */
    public function album($id)
    {
        try {
            $product = Product::with('projectCategory')
                ->where('type', 'Album')
                ->find($id);
            if ($product) {
                $this->solrController->kiki_product_delete_by_id($id);
                $this->productSolr($product);
                return "Album ID -$id record successfully updated";
            } else {
                return "Album ID -$id not found. Please recheck ID";
            }
        } catch (Exception $exception) {
            Log::error("Album album solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * album solr document delete
     * @return string
     */
    public function albumDeleteAll()
    {
        try {
            $this->solrController->delete_all_product();
            return "All albums record are deleted successfully";
        } catch (Exception $exception) {
            Log::error("Album album solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * album solr document delete by ID
     * @param $id
     */
    public function albumDelete($id)
    {
        try {
            $product = Product::with('projectCategory')
                ->where('type', 'Album')
                ->find($id);
            if ($product) {
                $this->solrController->kiki_product_delete_by_id($id);
                return "Album ID -$id record successfully Deleted";
            } else {
                return "Album ID -$id not found. Please recheck ID";
            }
        } catch (Exception $exception) {
            Log::error("Album album solr bulk upload error " . $exception->getMessage());
        }
    }

    /**
     * @param $song
     */
    private function songSolr($song)
    {
        try {
            if ($song) {
                $data = array(
                    'id' => $song->songId, //id is required
                    'Name' => $song->name,
                    'Description' => $song->description,
                    'ISRC Code' => $song->isbc_code,
                    'Primary Category' => $song->category ? $song->category->name : '',
                    'Sub Category' => $song->subCategory ? $song->subCategory->name : '',
                    //'Image URL' => $song->image ? Config('constants.bucket.url') . Config('filePaths.front.song-image') . $song->image : '',
                    'Image URL' => $song->image ? $song->image : '',
                    'Song URL' => $song->streamUrl ? $song->streamUrl : '',
                    'Primary Artist' => $song->primaryArtists()->lists('name')->toArray(),
                    'Featured Artist' => $song->featuredArtists()->lists('name')->toArray(),
                    'Search Tags' => $song->search_tag,
                    'Mood' => $song->mood()->lists('name')->toArray(),
                    'Genre' => $song->genres()->lists('Name')->toArray(),
                    'Duration' => $song->durations,
                    'Upload Date' => $song->uploaded_date,
                    'End Date' => $song->end_date,
                    'Release Date' => $song->release_date,
                    'Status' => $song->status == 1 ? 'Active' : "Inactive",
                );
                $this->solrController->kiki_song_create_document($data);
            }
        } catch (Exception $exception) {
            Log::error("song solr bulk upload create error " . $exception->getMessage());
        }
    }

    /**
     * @param $artist
     */
    private function artistSolr($artist)
    {
        try {
            if ($artist) {
                $similar_id = $artist->similarArtists()->lists('similar_artist_id')->toArray();
                $similarArtist = '';
                if ($similar_id) {
                    $similarArtist = Artist::whereIn('artistId', $similar_id)->lists('name')->toArray();
                }

                $data = array(
                    'id' => $artist->artistId, //id is required
                    'Name' => $artist->name,
                    'Description' => $artist->description,
                    //'Image URL' => $artist->image ? Config('constants.bucket.url').Config('filePaths.front.artist').$artist->image : '' ,
                    'Image URL' => $artist->image ? $artist->image : '',
                    'Search Tags' => $artist->search_tag,
                    'Similar Artists' => $similarArtist,
                    'Status' => $artist->status == 1 ? 'Active' : "Inactive",
                );
                $this->solrController->kiki_artist_create_document($data);
            }
        } catch (Exception $exception) {
            Log::error("artist solr bulk upload create error" . $exception->getMessage());
        }
    }

    /**
     * @param $playList
     */
    private function playListSolr($playList)
    {
        try {
            if ($playList) {
                $data = array(
                    'id' => $playList->id, //id is required
                    'Name' => $playList->name,
                    'Description' => $playList->description,
                    'Type' => $playList->type_name,
                    'Release Date' => $playList->release_date ? date('Y-m-d', strtotime($playList->release_date)) : '',
                    'Publish Date' => $playList->publish_date ? date('Y-m-d', strtotime($playList->publish_date)) : '',
                    'END Date' => $playList->expiry_date ? date('Y-m-d', strtotime($playList->expiry_date)) : '',
                    //'Image URL' => $playList->image ? Config('constants.bucket.url'). Config('filePaths.front.playlist') .$playList->image : '',
                    'Image URL' => $playList->image ? $playList->image : '',
                    'Status' => $playList->status == 1 ? 'Active' : "Inactive",
                );
                $this->solrController->kiki_playlist_create_document($data);
            }
        } catch (Exception $exception) {
            Log::error("Playlist solr bulk upload create error " . $exception->getMessage());
        }
    }

    /**
     * @param $product
     */
    private function productSolr($product)
    {
        try {

            if ($product) {
                $data = array(
                    'id' => $product->id, //id is required
                    'Name' => $product->name,
                    'Type' => $product->type,
                    'Description' => $product->description,
                    'Primary Artist' => $product->artists()->lists('name')->toArray(),
                    //'Image URL' => $product->image ? Config('constants.bucket.url') . Config('filePaths.front.product') . $product->image : '',
                    'Image URL' => $product->image ? $product->image : '',
                    'Primary Category' => $product->projectCategory ? $product->projectCategory->name : '',
                    'Status' => $product->status == 1 ? 'Active' : "Inactive",
                );
                $this->solrController->kiki_product_create_document($data);
            }
        } catch (Exception $exception) {
            Log::error("product solr error " . $exception->getMessage());
        }
    }
}
