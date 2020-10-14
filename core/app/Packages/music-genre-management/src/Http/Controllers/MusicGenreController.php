<?php

namespace MusicGenre\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use Config;
use File;
use Illuminate\Http\Request;
use Log;
use DB;
use MusicGenre\Models\MusicGenre;
use Sentinel;
use Datatables;

class MusicGenreController extends Controller
{

    private $genreImagePath;

    public function __construct()
    {
        $this->genreImagePath = Config::get('filePaths.genre');
    }

    public function index()
    {
        return view('MusicGenre::list');
    }

    public function getGenres(Request $request)
    {
        // return MusicGenre::with('songsActive')->limit(10)->get();
        try {
            $user = Sentinel::getUser();
            /**
             * if you use datatables without select this will change field to capitalize
             */
            $model = MusicGenre::leftJoin('song_genres', 'audio_genre.GenreId', '=', 'song_genres.genre_id')
                    ->leftJoin('songs', function ($q){
                        $q->on('song_genres.song_id', '=', 'songs.songId');
                        $q->where('songs.status', '=', 1);
                    })
                    ->groupBy('audio_genre.GenreID')
                    ->select('audio_genre.Name', 'audio_genre.Description', 'audio_genre.status', 'audio_genre.color', 'audio_genre.GenreID', DB::raw("COUNT(songs.songId) as song_count"));
            return Datatables::of($model)
                ->editColumn('status', function ($value) {
                    return $value->status == 1 ? 'Activated' : 'Inactivated';
                })
                ->editColumn('color', function ($value) {
                    if ($value->color != null) {
                        return '<center><span class="badge badge-sm" style=" background-color:' . $value->color . '">' . $value->color . '</span></center>';
                    } else {
                        return '<center>-</center>';
                    }
                })
                ->addColumn('toggle_status', function ($value) {
                    if ($value->status == 1) {
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue genre-status-toggle " data-id="' . $value->GenreID . '"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                    } else {
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue genre-status-toggle " data-id="' . $value->GenreID . '"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                    }
                })
                ->addColumn('edit', function ($value) use ($user) {
                    if ($user->hasAnyAccess(['admin.music-genres.show', 'admin']))
                        return '<center><a href="#" class="blue" onclick="window.location.href=\'' . route('admin.music-genres.show', $value->GenreID) . '\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Genre"><i class="fa fa-pencil"></i></a></center>';
                })
                ->addColumn('song_count', function ($value) {
                    return $value->song_count;
                })
                ->make(true);
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return Datatables::of(collect())->make(true);
        }
    }

    function create()
    {
        return view('MusicGenre::add');
    }

    function store(Request $request)
    {
        try {
            $genre = new MusicGenre();
            $genre->name = $request->get('name');
            $genre->tags = $request->get('tags');
            $genre->description = $request->get('description');
            $genre->status = 1;
            $genre->color = $request->get('color');

            $genre->save();
            if ($request->hasFile('image')) {
                $image = new ImageController();

                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'genre-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $image->upload($this->genreImagePath, $aImage, $fileName, $genre->GenreID);

                $genre->update([
                    'icon_image' => $fileName
                ]);
            }

            return redirect(route('admin.music-genres.index'))
                ->with([
                    'success' => true,
                    'success.message' => "Successfully added new music genre",
                    'success.title' => 'Success']);
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect(route('admin.music-genres.create'))
                ->with([
                    'error' => true,
                    'error.message' => 'Error adding new music genre. Please try again. Ex: ' . $exceptionId,
                    'error.title' => 'Oops !!'
                ]);
        }
    }

    function uploadImage($file, $path, $fileName)
    {
        if (!file_exists($path)) {
            Log::info("Creating directory " . $path);
            File::makeDirectory($path, 0777, true);
        }

        $file->move($path, $fileName);
    }

    function show($music_genres)
    {
        try {
            $genre = MusicGenre::find($music_genres);
            $image = [];
            $image_config = [];
            if ($genre->Icon_Image) {
                array_push($image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.genre') . $genre->Icon_Image . "'>");
                array_push($image_config, array(
                    'caption' => $genre->Icon_Image,
                    'type' => 'image',
                    'key' => $genre->GenreID,
                    'url' => url('admin/music-genres/image-delete'),
                ));
            }
            return view('MusicGenre::edit', compact('genre', 'image', 'image_config'));
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect(route('admin.music-genre.index'))
                ->with([
                    'error' => true,
                    'error.message' => "Please try again. Ex: " . $exceptionId,
                    'error.title' => 'Oops !!'
                ]);
        }
    }


    function update(Request $request, $music_genres)
    {
        try {
            $genre = MusicGenre::find($music_genres);
            $genre->name = $request->get('name');
            $genre->color = $request->get('color');
            $genre->description = $request->get('description');
            $genre->tags = $request->get('tags');

            if ($request->hasFile('image')) {
                $image = new ImageController();
                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'genre-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $image->upload($this->genreImagePath, $aImage, $fileName, $genre->GenreID);
                $genre->icon_image = $fileName;
            }else{
                if($request->get('image_delete') == 1)
                    $genre->icon_image = '';
            }


            $genre->save();

            return redirect(route('admin.music-genres.index'))
                ->with([
                    'success' => true,
                    'success.message' => "Successfully updated music genre",
                    'success.title' => 'Success']);
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage(), $request->all());
            return redirect(route('admin.music-genres.view', $music_genres))
                ->with([
                    'error' => true,
                    'error.message' => 'Error updating music genre. Please try again. Ex: ' . $exceptionId,
                    'error.title' => 'Oops !!'
                ]);
        }
    }

    function toggleStatus($music_genres)
    {
        try {
            $genre = MusicGenre::find($music_genres);
            $genre->Status = $genre->Status == 1 ? 0 : 1;
            $genre->save();
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
            $genre = MusicGenre::find($id);
            $genre->update([
                'icon_image' => ''
            ]);

            // activity
            /*if (Sentinel::getUser()) {
                parent::activity_create('Deleted product image.');
            }*/
        }
        return 1;
    }
}
