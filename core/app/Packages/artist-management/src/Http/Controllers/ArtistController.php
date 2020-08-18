<?php

namespace ArtistManage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SolrController;
use ArtistManage\Models\Artist;
use ArtistManage\Models\SimilarArtist;
use Config;
use DB;
use File;
use Illuminate\Http\Request;
use Log;
use Sentinel;
use Datatables;
use Response;
use App\Http\Controllers\ImageController;
use SongManage\Models\SongPrimaryArtists;
use Exception;

class ArtistController extends Controller
{

    private $artistImagePath;
    private $solrController;

    public function __construct(SolrController $solrController)
    {
        $this->artistImagePath = Config::get('filePaths.artist-images');
        $this->solrController = $solrController;
    }

    public function index()
    {
        return view('ArtistManage::list');
    }

    public function getArtists(Request $request)
    {

        $limit = $request->input('length');
        $start = $request->input('start');

        $search = $request->input('search.value');


        if (!$search) {
            try {
                $user = Sentinel::getUser();
                return Datatables::of(
                    Artist::leftJoin('song_primary_artists', 'songs_artists.artistId', '=', 'song_primary_artists.artist_id')
                        ->leftJoin('songs', function ($q){
                            $q->on('song_primary_artists.song_id', '=', 'songs.songId');
                            $q->where('songs.status', '=', 1);
                        })
                        ->groupBy('songs_artists.artistId')
                        ->select('songs_artists.artistId', 'songs_artists.name', 'songs_artists.description', 'songs_artists.status', DB::raw("COUNT(songs.songId) as songs_count"))
                        ->with('songs')
                )
                    ->editColumn('status', function ($value) {
                        return $value->status == 1 ? 'Activated' : 'Inactivated';
                    })
                    ->editColumn('songs_count', function ($value) {
                        return $value->songs_count;
                    })
                    ->editColumn('toggle-status', function ($value) {
                        if ($value->status == 1) {
                            return '<center><a href="javascript:void(0)" form="noForm" class="blue artist-status-toggle " data-id="' . $value->artistId . '"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                        } else {
                            return '<center><a href="javascript:void(0)" form="noForm" class="blue artist-status-toggle " data-id="' . $value->artistId . '"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                        }
                    })
                    ->addColumn('edit', function ($value) use ($user) {
                        if ($user->hasAnyAccess(['admin.artists.show', 'admin']))
                            return '<center><a href="#" class="blue" onclick="window.location.href=\'' . route('admin.artists.show', $value->artistId) . '\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Artist"><i class="fa fa-pencil"></i></a></center>';
                    })
                    ->make(true);
            } catch (\Throwable $exception) {
                $exceptionId = rand(0, 99999999);
                Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
                return Datatables::of(collect())->make(true);
            }
        } else {

            $user = Sentinel::getUser();
            $data = Artist::leftJoin('song_primary_artists', 'songs_artists.artistId', '=', 'song_primary_artists.artist_id')
                ->leftJoin('songs', function ($q){
                    $q->on('song_primary_artists.song_id', '=', 'songs.songId');
                    $q->where('songs.status', '=', 1);
                })
                ->groupBy('songs_artists.artistId')
                ->select('songs_artists.artistId', 'songs_artists.name', 'songs_artists.description', 'songs_artists.status', DB::raw("COUNT(songs.songId) as songs_count"))
                ->with('songs')->where(function($q) use ($search) {
                $q->where('songs_artists.name', 'like', '%'.$search.'%');
            });

            $totalData = count($data->get());
            $totalFiltered = $totalData;

            $data = $data->offset($start)->limit($limit)
                ->get();

            $jsonList = array();
            $i=1;
            foreach ($data as $key => $artist) {

                $status = $artist->status == 1 ? 'Activated' : 'Inactivated';

                $toggleStatus = null;
                if ($artist->status == 1) {
                    $toggleStatus = '<center><a href="javascript:void(0)" form="noForm" class="blue artist-status-toggle " data-id="' . $artist->artistId . '"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                } else {
                    $toggleStatus = '<center><a href="javascript:void(0)" form="noForm" class="blue artist-status-toggle " data-id="' . $artist->artistId . '"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                }

                $editField = null;
                if ($user->hasAnyAccess(['admin.artists.show', 'admin'])) {
                    $editField = '<center><a href="#" class="blue" onclick="window.location.href=\'' . route('admin.artists.show', $artist->artistId) . '\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Artist"><i class="fa fa-pencil"></i></a></center>';
                }

                $dd = array(
                    'artistId' => $artist->artistId,
                    'name' => $artist->name,
                    'description' => $artist->description,
                    'songs_count' => $artist->songs_count,
                    'status' => $status,
                    'toggle-status' => $toggleStatus,
                    'edit' => $editField,
                );

                array_push($jsonList, $dd);

            }
            return Response::json(array(
                'data' => $jsonList,
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered)
            ));
        }
    }

    function create()
    {
        $artists = Artist::whereStatus(1)->get();
        return view('ArtistManage::add', compact('artists'));
    }

    function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $artist = new Artist;
            $artist->name = $request->get('name');
            $artist->description = $request->get('description');
            $artist->search_tag = $request->get('tags');
            $artist->status = 1;

            $similarArtists = [];
            if ($request->has('similar_artists') && count($request->get('similar_artists')) > 0) {
                foreach ($request->get('similar_artists') as $artistId) {
                    $similarArtists[] = new SimilarArtist([
                        "similar_artist_id" => $artistId
                    ]);
                }
            }
            $artist->save();

            $image = new ImageController();

            $aImage = $request->file('image');
            $ext = $aImage->getClientOriginalExtension();
            $fileName = 'artist-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
            $path = $image->upload('artists', $aImage, $fileName, $artist->artistId);

            //$artist->image = $path . '/' . $fileName;

            $artist->update([
                'image' => $fileName
            ]);

            $artist->similarArtists()->saveMany($similarArtists);
            $this->artistSolr($artist->artistId);
        });
        return redirect(route('admin.artists.index'))->with(['success' => true,
            'success.message' => "Successfully added new artist",
            'success.title' => 'Success']);
    }

    function uploadImage($file, $path, $fileName)
    {
        if (!file_exists($path)) {
            Log::info("Creating directory " . $path);
            File::makeDirectory($path, 0777, true);
        }

        $file->move($path, $fileName);
    }

    function show($artists)
    {
        try {
            $artist = Artist::with("similarArtists")->find($artists);
            $artists = Artist::whereStatus(1)
                ->where("artistId", "!=", $artist->artistId)
                ->get();
            $image = [];
            $image_config = [];
            if ($artist->image) {
                array_push($image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.artist') . $artist->image . "'>");
                array_push($image_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => $artist->id,
                    'url' => url('admin/artists/image-delete'),
                ));
            }

            $sim = [];
            $similarArtists = SimilarArtist::where('artist_id', $artist->artistId)->get();
            foreach ($similarArtists as $similarArtist) {
                $sim [] = $similarArtist->similar_artist_id;
            }
            $similar = Artist::whereStatus(1)->whereIn('artistId', $sim)->get();

            return view('ArtistManage::edit')
                        ->with(['artist' => $artist, 'artists' => $artists, 'image' => $image, 'image_config' => $image_config, 'similar' => $similar]);
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect(route('admin.artists.index'))->with([
                'error' => true,
                'error.message' => "Please try again. Ex: " . $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }


    function update(Request $request, $artists)
    {
        try {
            DB::transaction(function () use ($request, $artists) {
                $artist = Artist::find($artists);
                $artist->name = $request->get('name');
                $artist->description = $request->get('description');

                $artist->search_tag = $request->get('tags');
                if ($request->file('image')) {
                    $image = new ImageController();
                    $aImage = $request->file('image');
                    $ext = $aImage->getClientOriginalExtension();
                    $fileName = 'artist-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    $image->upload('artists', $aImage, $fileName, $artist->artistId);
                    $artist->image = $fileName;
                }else if($request->has('image_removed') && $request->get('image_removed') == 1){
                    $artist->image =  null;
                }

                $artist->save();

                $similarArtists = [];
                if ($request->has('similar_artists') && count($request->get('similar_artists')) > 0) {
                    foreach ($request->get('similar_artists') as $artistId) {
                        $similarArtists[] = new SimilarArtist([
                            "similar_artist_id" => $artistId
                        ]);
                    }
                }
                $artist->similarArtists()->delete();
                $artist->similarArtists()->saveMany($similarArtists);
                $this->solrController->kiki_artist_delete_by_id($artist->artistId);
                $this->artistSolr($artist->artistId);
            });
            return redirect(route('admin.artists.index'))->with(['success' => true,
                'success.message' => "Successfully updated artist",
                'success.title' => 'Success']);
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage(), $request->all());
            return redirect(route('admin.artists.index', $artists))->with([
                'error' => true,
                'error.message' => 'Error updating artist. Please try again. Ex: ' . $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function toggleStatus($artists)
    {
        try {
            $artist = Artist::find($artists);
            $artist->status = $artist->status == 1 ? 0 : 1;
            $artist->save();

            $this->solrController->kiki_artist_delete_by_id($artists);
            $this->artistSolr($artists);

        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return response()->json("error : " . $exceptionId, 403);
        }
    }

    public function imageDelete(Request $request)
    {
        if ($request->has('key')) {
            $id = $request->get('key');
            $songComposer = Artist::find($id);
            $songComposer->image = null;
            $songComposer->save();

            // activity
            /*if (Sentinel::getUser()) {
                parent::activity_create('Deleted product image.');
            }*/
            $this->solrController->kiki_artist_delete_by_id($id);
            $this->artistSolr($id);
            return 2;
        }
        return 1;
    }

    public function artistSearch(Request $request){

        $search = $request->get('term');
        $artists = [];
        if($search){
           $artists =  Artist::where('name', 'like', '%' . $search . '%')->where('status', 1)->limit(20)->orderBy('name', 'asc')->get();
        }

        return $artists;

    }

    private function activeSongs($artistId)
    {
        $count = SongPrimaryArtists::whereHas('songs', function ($q){
            $q->whereStatus(1);
        })->where('artist_id', $artistId)->count();

        return $count;
    }

    private function artistSolr($artistId){
        try {
            $artist = Artist::find($artistId);
            if($artist){
                $similar_id = $artist->similarArtists()->lists('similar_artist_id')->toArray();
                $similarArtist = '';
                if ($similar_id)
                    $similarArtist = Artist::whereIn('artistId', $similar_id)->lists('name')->toArray();
                $data = array(
                    'id' => $artist->artistId, //id is required
                    'Name' => $artist->name,
                    'Description' => $artist->description,
                    //'Image URL' => $artist->image ? Config('constants.bucket.url').Config('filePaths.front.artist').$artist->image : '' ,
                    'Image URL' => $artist->image ? $artist->image : '' ,
                    'Search Tags' => $artist->search_tag,
                    'Similar Artists' =>   $similarArtist ,
                    'Status' => $artist->status == 1 ? 'Active' : "Inactive",
                );
                Log::error($data);
               $this->solrController->kiki_artist_create_document($data);
            }
        }catch (Exception $exception){
            Log::error("artist solr error ". $exception->getMessage());
        }
    }
}
