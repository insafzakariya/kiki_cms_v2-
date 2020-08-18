<?php
namespace PlaylistManage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SolrController;
use App\Http\Controllers\TrackController;
use App\Models\Policy;
use Config;
use Datatables;
use DB;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Log;
use PlaylistManage\Models\AudioPlaylist;
use PlaylistManage\Models\AudioPlaylistSongs;
use PlaylistManage\Models\PlaylistType;
use Response;
use Sentinel;
use SongManage\Models\Songs;

class PlaylistController extends Controller {

    private $imageController;
    private $trackController;
    private $playlistImagePath;
    private $solrController;

    public function __construct(ImageController $imageController,
                                TrackController $trackController, SolrController $solrController)
    {
        $this->playlistImagePath = Config::get('filePaths.playlist-image');
        $this->imageController = $imageController;
        $this->trackController = $trackController;
        $this->solrController = $solrController;
    }

    public function step1View(Request $request)
    {

        $contentPolicies = Policy::where('PolicyType', 10)->get();
        $advertisementPolicies = Policy::where('PolicyType', 6)->get();
        $playlistTypes = PlaylistType::where('status', 1)->get();

        $playlist = null;
        $image = [];
        $image_config = [];
        if($request->has("id")) {
            $playlist = AudioPlaylist::where('id', $request->get("id"))->first();
            if ($playlist) {
                if($playlist->image){
                    array_push($image, "<img style='height:190px' src='" .  Config('constants.bucket.url'). Config('filePaths.front.playlist') .$playlist->image . "'>");
                    array_push($image_config, array(
                        'caption' => '',
                        'type' => 'image',
                        'key' => $playlist->id,
                    ));
                }
            }
        }


        return view('PlaylistManage::step-1')->with(['content_policies' => $contentPolicies,
            'advertisement_policies' => $advertisementPolicies,
            'playlist_types' => $playlistTypes, 'data' => $playlist, 'image' => $image, 'image_config' => $image_config]);
    }

    public function step1Save(Request $request)
    {

        $playlistId = null;
        if ($request->has('id')) {
            $playlistId =$request->get('id');
        }

        $playlistTypeCode = in_array($request->type, array(null, "")) ? "g" : $request->type;
        $playlistTypeName = PlaylistType::whereCode($playlistTypeCode)->whereStatus(1)->first();
        if($playlistTypeName){
            $playlistTypeName = $playlistTypeName->name;
        }else{
            $playlistTypeName = null;
        }

        $policies[] = [
            "policy_id" =>  $request->advertisement_policy,
            "pollicy_type" => 2,
        ];
        foreach (json_decode($request->content_policies) as $key => $content_policy){
            $policies[] = [
                "policy_id" => $content_policy,
                "pollicy_type" => 1,

            ];
        }


        if (!$playlistId) {
            $playlist = AudioPlaylist::create([
                'name' => $request->name,
                'status' => -1,
                'publish_date' => $request->publish_date,
                'playlist_type' => $playlistTypeCode,
                'type_name' => $playlistTypeName,
                'description' => $request->description,
                'content_policy' => "",
                'advertisement_policy' => "",
                'release_date' => $request->release_date,
                'expiry_date' => $request->end_date
            ]);
            $playlist->policies()->sync($policies);
            $playlistId = $playlist->id;

        } else {
            $existingPlaylist = AudioPlaylist::find($playlistId);
            $existingPlaylist->name = $request->name;
            $existingPlaylist->publish_date = $request->publish_date;
            $existingPlaylist->playlist_type = $playlistTypeCode;
            $existingPlaylist->type_name = $playlistTypeName;
            $existingPlaylist->description = $request->description;
            $existingPlaylist->content_policy = "";
            $existingPlaylist->advertisement_policy = $request->advertisement_policy;
            $existingPlaylist->release_date = $request->release_date;
            $existingPlaylist->expiry_date = $request->end_date;
            $existingPlaylist->save();
            $existingPlaylist->policies()->sync($policies);
        }

        $this->reorderOldPlaylist($playlistId);

        $image = $request->file('image');
        $fileName = null;
        $path = null;
        if (File::exists($image)) {
            $file = $image;
            $extn = $file->getClientOriginalExtension();
            $fileName = 'playlist-' . date('YmdHis') . '.' . $extn;

            $path = $this->imageController->Upload($this->playlistImagePath, $file, $fileName, $playlistId);

            $playlist = AudioPlaylist::find($playlistId);
            $playlist->image = $fileName;
            $playlist->save();

            if ($playlist->playlist_type == 'g') {
                $this->solrController->kiki_playlist_delete_by_id($playlist->id);
                $this->playListSolr($playlist->id);
            }

        }

        return redirect('admin/playlist/step-2?id='.$playlistId);
    }
    function reorderOldPlaylist($playlistId){
        $playlistSongs = AudioPlaylistSongs::where("playlist_id", $playlistId)
            ->orderBy("id", "ASC")
            ->get();
        if($playlistSongs->where('song_order', null)->count() > 0){
            foreach ($playlistSongs as $key => $playlistSong){
                $playlistSong->song_order = $key+1;
                $playlistSong->save();
            }
        }

    }
    function reorderPlaylist($playlistId){
        $playlistSongs = AudioPlaylistSongs::where("playlist_id", $playlistId)
            ->orderBy("id", "ASC")
            ->get();
        foreach ($playlistSongs as $key => $playlistSong){
            $playlistSong->song_order = $key+1;
            $playlistSong->save();
        }
    }
    public function step2View(Request $request)
    {
        $playlist = AudioPlaylist::find($request->get("id"));

        if($playlist && ($playlist->status == -1 || URL::previous() == url('admin/playlist/step-3?id='.$playlist->id))){
            return view('PlaylistManage::song-add')
                ->with('id', $playlist->id);
        }elseif($playlist){
            return redirect('admin/playlist/step-3?id='.$playlist->id);
        }

    }

    public function step2Save(Request $request)
    {
        $playlistId = null;
        if ($request->has("id")) {
            $playlistId = $request->get("id");
        } else {
            return response()->json(['status' => 'invalid_id'], 422);
        }

        $songs = $request->songs;
        $lastSongOfPl = AudioPlaylistSongs::where("playlist_id", $playlistId)
            ->orderBy("song_order", "DESC")
            ->first();

        $data = array();
        $order = $lastSongOfPl ? $lastSongOfPl->song_order : 0;
        foreach ($songs as $song) {
            $order++;
            $data [] = [
                'playlist_id' => $playlistId,
                'song_id' => $song,
                'song_order' => ($order),
            ];
        }

        $playlistSongs = AudioPlaylistSongs::insert($data);

        if ($playlistSongs == 1) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error'], 422);

    }

    public function step3View(Request $request)
    {
        $playlistId = $request->get("id");
        return view('PlaylistManage::step-3', compact('playlistId'));
    }
    function setTableSorting($data, $request){
        try{
            $orderDetails = $request->get("order")[0];
            $orderColumn = $request->get("columns")[$orderDetails["column"]];
            if(in_array($orderColumn["name"], array("song_order", "song_id", "song.name", "song.primaryArtists.name", "song.genres.name", "song.isbc_code", "song.category.name", "song.writer.name", "song.music.name"))){
                if("desc" == $orderDetails["dir"]){
                    $data = $data->sortByDesc($orderColumn["name"]);
                }else{
                    $data = $data->sortBy($orderColumn["name"]);
                }
            }
        }catch (\Throwable $exception){
            $data = $data->sortBy("song_order");
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage(), array_merge($request->all()));
        }
        return $data;
    }
    public function getSongsOfPlaylist(Request $request)
    {
        try{

            $playlistId = $request->get('playlist_id');
            $limit = $request->input('length');
            $start = $request->input('start');


            $data = null;

            $data= AudioPlaylistSongs::select('id', 'playlist_id', 'song_id', 'song_order')
                ->where('playlist_id', $playlistId);


            if($request->has("search") && $request->get("search")["value"] != "") {
                $text = $request->get("search")["value"];

                $data->whereHas('song', function ($q1) use ($text) {
                    $q1->where(function ($q2) use ($text) {
                        $q2->orWhere('isbc_code', 'like', '%' . $text . '%')
                            ->orWhere(function($q3) use ($text) {
                                $q3->whereHas('primaryArtists', function ($q2) use ($text) {
                                    $q2->where('name', 'like', '%' . $text . '%')->whereStatus(1);
                                });
                            })
                            ->orWhere('name', 'like', '%' . $text . '%');
                    })
                        ->whereStatus(1);

                });



            }

            $data = $data->get();
            $data = $this->setTableSorting($data, $request);
            $totalFiltered = $totalData = $data->count();

            if($limit == -1){
                $filteredData = $data;
            }else{
                $filteredData = $data->forPage(($start/$limit) +1, $limit);
            }


            $jsonList = array();
            $i=1;
            foreach ($filteredData as $key => $song) {
                if($song->song){

                    $proTypes = "";
                    if($song->song->products){
                        foreach($song->song->products as $product){
                            $proTypes .= $product->type.", ";
                        }
                    }


                    $dd = array(
                        'order' => $song->song_order,
                        'id' => $song->song_id,
                        'name' => $song->song && $song->song->name != "" ? $song->song->name : "-",
                        'genre_name' => $song->song->getGenreString(),
                        'artist_name' => $song->song->getArtistsString(),
                        'product_type' => $proTypes,
                        'album' => "-",
                        'isrc' => $song->song->isbc_code,
                        'category_name' => $song->song->category && $song->song->category->name != "" ? $song->song->category->name : "-",
                        'writer_name' => $song->song->writer && $song->song->writer->name != "" ? $song->song->writer->name : "-",
                        'music_by' => $song->song && $song->song->music && $song->song->music->where("status", 1) != "" ? $song->song->music->name : "-",
                        'action' => "<center><a href='#' class='blue' bid='{$song->id}' onclick='confirmAlert($song->id)' data-toggle=\"tooltip\" data-placement=\"top\" title=\"Remove Song\"><i class=\"fa fa-trash\"></i></a></center>",
                    );

                    array_push($jsonList, $dd);
                    $i++;
                }

            }
            return Response::json(array(
                'data' => $jsonList,
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered)
            ));

        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage(), array_merge($request->all(), ["playlistId" => $playlistId]));
            return Response::json(array(
                'data' => [],
                "draw" => intval($request->input('draw')),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "exceptionId" => $exceptionId
            ));
        }
    }

    public function removeSongFromPlaylist(Request $request)
    {
        $song = AudioPlaylistSongs::find($request->id);
        if ($song) {
            $song->delete();
            $this->reorderPlaylist($song->playlist_id);
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }



    public function step3Save(Request $request)
    {
        $playlist = AudioPlaylist::find($request->get("id"));
        $playlist->status = 1;
        $playlist->save();

        if ($playlist) {
            if ($playlist->playlist_type == 'g') {
                $this->solrController->kiki_playlist_delete_by_id($playlist->id);
                $this->playListSolr($playlist->id);
            }

            return redirect('admin/playlist')->with(['success' => true,
                'success.message' => 'Playlist Created successfully!',
                'success.title' => 'Well Done!']);
        } else {
            return redirect('admin/playlist/step-1?id='.$request->get("id"))->with(['error' => true,
                'error.message' => 'Playlist Creation Failed!',
                'error.title' => 'Error!']);
        }


    }


    public function listView()
    {
        return view( 'PlaylistManage::list' );
    }

    public function jsonList(Request $request)
    {

        return Datatables::of(
            AudioPlaylist::select('id', 'name', 'type_name', 'publish_date', 'release_date',
                'expiry_date', 'status')
        )
            ->addColumn('songs_count', function ($value){
                return $value->activeSongs()->count();
            })
            ->addColumn('toggle-status', function ($value){
                if ($value->status == -1) {
                    return "<center> - <center>";
                }else
                    if ($value->status == 1) {
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue playlist-status-toggle " data-id="'.$value->id.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                    } else {
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue playlist-status-toggle " data-id="' . $value->id . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                    }
            })
            ->addColumn('edit', function ($value){
                return '<center><a href="#" class="blue" onclick="window.location.href=\''.url('admin/playlist/step-1/?id='.$value->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit Playlist"><i class="fa fa-pencil"></i></a></center>';
            })
//            ->editColumn('expiry_date', function ($value){
//                $currentDate = strtotime(date('Y-m-d'));
//                $endDate = null;
//                if ($value->expiry_date) {
//                    $endDate = strtotime($value->expiry_date);
//                }
//
//                if(($endDate != null && $endDate != "") && $currentDate >= $endDate) {
//                    return '<label style="display: block; border: 1px solid #f00; min-height: 100%; height: auto !important; height: 100%; margin: 0; padding: 0">'.$value->expiry_date.'</label>';
//                } else {
//                    return '<label>'.$value->expiry_date.'</label>';
//                }
//            })
            ->editColumn('status', function ($value){
                if ($value->status == -1) {
                    return "INCOMPLETE";
                }else
                    if ($value->status == 1) {
                        return 'ACTIVE';
                    } else {
                        return 'INACTIVE';
                    }
            })
            ->make(true);

    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $state = $request->state;

        $song = AudioPlaylist::find($id);
        if ($song) {
            $song->status = $state;
            $song->save();
            if ($song->playlist_type == 'g') {
                $this->solrController->kiki_playlist_delete_by_id($song->id);
                $this->playListSolr($song->id);
            }
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }

    public function songAddDataLoad(Request $request)
    {
        $limit = $request->input('length');
        $start = $request->input('start');

        $type = $request->type;
        $text = $request->text;

        $data = null;

        if (!$type || !$text) {
            $data= null;
        } else {
            $data = Songs::select('songId', 'name', 'isbc_code', 'status', 'publisherId', 'musicId', 'categoryId', 'artistId', 'genreId')
                ->with([
                    "primaryArtists" => function($query){
                        $query->select('artistId', 'name');
                    },
                    "genres" => function($query){
                        $query->select('GenreID', 'Name');
                    },
                    "category" => function($query){
                        $query->select('categoryId', 'name', 'status');
                    },
                    "music" => function($query){
                        $query->select('id', 'name');
                    },
                    "publisher" => function($query){
                        $query->select('publisherId', 'name');
                    }
                ])
                ->where('status', 1);
            if ($type == "artist") {
                $data->whereHas('primaryArtists', function ($query) use ($text){
                    $query->where('name', 'like', '%'.$text.'%')->whereStatus(1);
                });
            }else if ($type == "genre") {
                $data->whereHas('genres', function ($query) use ($text){
                    $query->where('name', 'like', '%'.$text.'%')->whereStatus(1);
                });
            }else if ($type == "product") {
                $data->whereHas('products', function ($query) use ($text){
                    $query->where('name', 'like', '%'.$text.'%')->whereStatus(1);
                });
            }else if ($type == "category") {
                $data->whereHas('category', function ($query) use ($text){
                    $query->where('name', 'like', '%'.$text.'%')->whereStatus(1);
                });
            }else if ($type == "isrc") {
                $data->where('isbc_code', 'like', '%'.$text.'%');
            }else if($type == "name") {
                $data->where('name', 'like', '%'.$text.'%');
            }
        }


        if ($data) {
            Log::info("Start DB query");
            $data = $data
                ->orderBy('songId', 'desc')
                ->get();
            Log::info("End getting total data");
            $totalData = $data->count();
            Log::info("End getting total count");
            $filterData = $data->forPage(($start/$limit) +1, $limit);
            $totalFiltered = $filterData->count();

            $jsonList = array();
            $i = 1;
            foreach ($filterData as $key => $song) {

                $dd = array();

                array_push($dd, "<center><input type='checkbox'></center>");

                if ($song->songId != "") {
                    array_push($dd, $song->songId);
                } else {
                    array_push($dd, "-");
                }

                if ($song->isbc_code != "") {
                    array_push($dd, $song->isbc_code);
                } else {
                    array_push($dd, "-");
                }

                if ($song->name != "") {
                    array_push($dd, $song->name);
                } else {
                    array_push($dd, "-");
                }
                array_push($dd, $song->getArtistsString());


                array_push($dd, $song->getGenreString());

                if (isset($song->category)) {
                    array_push($dd, $song->category->name);
                } else {
                    array_push($dd, "-");
                }

                if ($song->music) {
                    array_push($dd, $song->music->name);
                } else {
                    array_push($dd, "-");
                }

                if ($song->publisher) {
                    array_push($dd, $song->publisher->name);
                } else {
                    array_push($dd, "-");
                }

                array_push($dd, '<center><button class="btn btn-info addSong">Add</button></center>');


                array_push($jsonList, $dd);
                $i++;
            }
            Log::info("End getting songAddDataLoad");
            return Response::json(array(
                'data' => $jsonList,
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalData)
            ));
        }

        return Response::json(array(
            'data' => [],
            "draw" => intval($request->input('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0
        ));
    }

    function orderSongs(Request $request){
        try {
            DB::transaction(function () use ($request){
                foreach ($request->get('order') as $songOrder){
                    $song = AudioPlaylistSongs::wherePlaylistId($request->get('playlist_id'))
                        ->whereSongId($songOrder['song_id'])->first();
                    if($song){
                        $song->song_order = $songOrder['order'];
                        $song->save();
                    }
                }
            });
            return response()->json("Successfully updated", 200);
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage(), $request->all());
            return response()->json("Error : Ex " . $exceptionId, 422);
        }
    }


    private function playListSolr($playlistId)
    {
        try {
            $playList = AudioPlaylist::find($playlistId);
            if ($playList) {
                $data = array(
                    'id' => $playlistId, //id is required
                    'Name' => $playList->name,
                    'Description' => $playList->description,
                    'Type' => $playList->type_name,
                    'Release Date' => $playList->release_date ? date('Y-m-d', strtotime($playList->release_date)) : '',
                    'Publish Date' => $playList->publish_date ? date('Y-m-d', strtotime($playList->publish_date)) : '',
                    'END Date' => $playList->expiry_date ? date('Y-m-d', strtotime($playList->expiry_date)) : '',
                    //'Image URL' => $playList->image ? Config('constants.bucket.url'). Config('filePaths.front.playlist') .$playList->image : '',
                    'Image URL' => $playList->image ? $playList->image : '',
                    'Status' => $playList->status == 1 ? 'Active' : "Inactive"

                );
                $newDAta = $this->solrController->kiki_playlist_create_document($data);
                //Log::error($newDAta);
            }
            // return $song;
        } catch (Exception $exception) {
            Log::error("product solr error " . $exception->getMessage());
        }
    }

}
