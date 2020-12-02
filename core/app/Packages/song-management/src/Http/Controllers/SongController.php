<?php

namespace SongManage\Http\Controllers;

use App\Classes\SongSmil;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SolrController;
use App\Http\Controllers\TrackController;
use App\Models\Policy;
use ArtistManage\Models\Artist;
use Carbon\Carbon;
use Config;
use Datatables;
use Exception;
use File;
use Illuminate\Http\Request;
use Log;
use MoodManage\Models\Mood;
use Response;
use Session;
use SongManage\Models\AudioGenre;
use SongManage\Models\Lyricsts;
use SongManage\Models\MoodSongs;
use SongManage\Models\Product;
use SongManage\Models\Project;
use SongManage\Models\SongComposer;
use SongManage\Models\SongGenres;
use SongManage\Models\SongPrimaryArtists;
use SongManage\Models\SongProducts;
use SongManage\Models\SongProjects;
use SongManage\Models\SongPublisher;
use SongManage\Models\Songs;
use SongsCategory\Models\SongsCategory;
use App;

class SongController extends Controller
{

    private $imageController;
    private $trackController;
    private $solrController;

    public function __construct(ImageController $imageController,
                                TrackController $trackController, SolrController $solrController)
    {
        $this->imageController = $imageController;
        $this->trackController = $trackController;
        $this->solrController = $solrController;
    }

    /**
     * @param Request $request
     * @param null $id
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function step1View(Request $request, $id = null)
    {

        $artists = [];
        $categories = SongsCategory::where('status', 1)->where('parent_cat', '=', 0)->orderBy('name')->get();
        $moods = Mood::where('status', 1)->orderBy('name')->get();
        $genre = AudioGenre::where('status', 1)->orderBy('Name')->get();
        $lyrics = Lyricsts::where('status', 1)->orderBy('name')->get();
        $projects = Project::where('status', 1)->orderBy('name')->get();
        $products = Product::where('status', 1)->orderBy('name')->get();
        $songComposers = SongComposer::where('status', 1)->orderBy('name')->get();
        $publishers = SongPublisher::where('status', 1)->orderBy('name')->get();

        $song = null;
        $subCategories = [];
        $moodIds = $projectIds = $productIds = $genreIds = [];
        $fa_artists = [];
        $pa_artists = [];
        if ($request->exists('type')) {
            Session::forget('create_song_id');
            //unset($_SESSION['create_song_id']);
        }
        if ($request->exists('product_id')) {
            $productIds = [$request->get('product_id')];
        }

        //if (Session::has('create_song_id') && Session::has('create_song_type')) {
        if ($id) {
            $songId = \Session::get('create_song_id');
            $type = \Session::get('create_song_type');

            /**
             * need to pass song id and get all related data or can use song->with()
             */
            /*$fa_artists = Artist::where('status', 1)
            ->orderBy('name')
            ->whereHas('songArtists', function ($query) {
            $query->where('type', 'fa');
            })
            ->get();
            $pa_artists = Artist::where('status', 1)
            ->orderBy('name')
            ->whereHas('songArtists', function ($query) {
            $query->where('type', 'pa');
            })
            ->get();*/

            $song = Songs::where('songId', $id)
                ->with(['mood', 'primaryArtists', 'featuredArtists', 'projects', 'products', 'genres', 'writer', 'composer', 'publisher'])
                ->first();

            $moodIds = $song->mood ? $song->mood->lists('id')->toArray() : [];
            $projectIds = $song->projects ? $song->projects->lists('id')->toArray() : [];
            $productIds = $song->products ? $song->products->lists('id')->toArray() : [];
            $genreIds = $song->genres ? $song->genres->lists('GenreID')->toArray() : [];

            $subCategories = SongsCategory::where('status', 1)->where('parent_cat', '=', $song->categoryId)->get();
        }

        return view('SongManage::step-1')->with(['artists' => $artists, 'categories' => $categories,
            'moods' => $moods, 'genres' => $genre, 'lyrics' => $lyrics, 'projects' => $projects,
            'products' => $products, 'song_composers' => $songComposers, 'data' => $song,
            'sub_category' => $subCategories,
            'publishers' => $publishers,
            'mood_ids' => $moodIds,
            'projectIds' => $projectIds,
            'productIds' => $productIds,
            'genreIds' => $genreIds,
            'fa_artists' => $fa_artists,
            'pa_artists' => $pa_artists,
        ]);
    }

    public function getSubCategoriesByParentId($id)
    {
        $subCategories = SongsCategory::where('status', 1)->where('parent_cat', '=', $id)->get();

        return response($subCategories, 200);
    }

    public function step1Save(Request $request)
    {

        try {
            $featuredArtists = $request->featured_artists;
            $moods = $request->moods;
            $projects = $request->projects;
            $products = $request->products;
            $primaryArtists = $request->primary_artist;
            $genres = $request->song_genres;
            $endDate = $request->end_date ? $request->end_date : Carbon::parse('2999-12-12');
            $lyricistId = $this->saveLyricist($request->lyrics);
            $composerId = $this->saveComposer($request->composer);

            $songId = $request->song_id ? $request->song_id : null;
            /*if (Session::has('create_song_id') && Session::has('create_song_type')) {
            $songId = Session::get('create_song_id');
            }*/
            /*if($request->exists('product_id') AND !$songId AND is_integer($request->get('product_id')) ){
            $products = array_merge($products, [$request->get('product_id')] );
            // $products
            }*/

            $song = null;
            if (!$songId) {
                $productId = null;
                if ($request->get('product_id')) {
                    $productId = $request->get('product_id');
                    $products = array_unique(array_merge($products, [$productId]));
                }
                $song = Songs::create([
                    'name' => $request->name,
                    'isbc_code' => $request->isbc_code,
                    'description' => $request->description,
                    'search_tag' => $request->tags,
                    'status' => 0,
                    'stage' => 1,
                    'artistId' => null,
                    'featured_artists' => null,
                    'categoryId' => $request->primary_category,
                    'sub_categories' => $request->sub_category,
                    ///'moods' => null,
                    'genreId' => null,
                    'writerId' => $lyricistId,
                    'publisherId' => $request->song_publisher,
                    'project' => null,
                    'product' => $productId,
                    'line' => $request->p,
                    'release_date' => $request->release_date,
                    'uploaded_date' => $request->uploaded_date,
                    'end_date' => $endDate,
                    'musicId' => $composerId,
                ]);

                $this->saveMoods($song->songId, $moods);
                $this->saveProjects($song->songId, $projects);
                $this->saveProducts($song->songId, $products);
                //$this->savePrimaryArtists($song->songId, $primaryArtists);
                $this->saveFeaturedArtists($song->songId, $featuredArtists);
                $this->saveGenres($song->songId, $genres);
                $song->artistGenreCreate($primaryArtists, $genres);

                if ($song) {

                    $this->songSolr($song->songId);
                    Session::put('create_song_id', $song->songId);
                } else {
                    return 'opss something went wrong ';
                }

            } else {
                $song = Songs::where('songId', $songId)->first();
                $song->name = $request->name;
                $song->isbc_code = $request->isbc_code;
                $song->description = $request->description;
                $song->search_tag = $request->tags;
                //$song->artistId = null;
                //$song->featured_artists = null;
                $song->categoryId = $request->primary_category;
                $song->sub_categories = $request->sub_category;
                //$song->moods = null;
                //$song->genreId = null;
                $song->writerId = $lyricistId;
                $song->publisherId = $request->song_publisher;
                //$song->project = null;
                //$song->product = null;
                $song->line = $request->p;
                $song->release_date = $request->release_date;
                $song->uploaded_date = $request->uploaded_date;
                $song->end_date = $endDate;
                $song->musicId = $composerId;
                $song->save();

                $this->saveMoods($song->songId, $moods);
                $this->saveProjects($song->songId, $projects);
                $this->saveProducts($song->songId, $products);
                //$this->savePrimaryArtists($song->songId, $primaryArtists);
                $this->saveFeaturedArtists($song->songId, $featuredArtists);
                $this->saveGenres($song->songId, $genres);
                $song->artistGenreCreate($primaryArtists, $genres);

            }

            if ($song) {
                $this->solrController->kiki_song_delete_by_id($song->songId);
                $this->songSolr($song->songId);
                return redirect('admin/song/step-2/' . $song->songId);
            } else {
                return redirect('admin/song/step-1/')->withErrors('Oppps something went wrong please try again.');
            }

        } catch (Exception $exception) {
            Log::error("Song step 1 save| Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect('admin/song/step-1/')->withErrors($exception->getMessage());
        }

    }

    public function step2View(Request $request, $id = null)
    {

        /*if (!Session::has('create_song_id')) {
        return view('errors.404');
        }*/

        try {

            $contentPolicies = Policy::where('PolicyType', 10)->get();
            $advertisementPolicies = Policy::where('PolicyType', 6)->get();
            $song = null;
            $content = [];
            if ($id) {
                /*$songId = \Session::get('create_song_id');
                $type = \Session::get('create_song_type');*/

                $song = Songs::with(['contentPolicies'])->where('songId', $id)->first();
                $content = $song['contentPolicies'] ? $song['contentPolicies'] : [];
                if ($content) {
                    $contentIds = $content->lists('PolicyID');
                    $contentPolicies = Policy::where('PolicyType', 10)->whereNotIn('PolicyID', $contentIds)->get();
                }
                return view('SongManage::step-2')
                    ->with([
                        'content_policies' => $contentPolicies,
                        'advertisement_policies' => $advertisementPolicies,
                        'content_count' => 0,
                        'content' => $content,
                        'data' => $song,
                    ]);
            } else {
                return view('errors.404');
            }
        } catch (Exception $exception) {
            Log::error("Song step-2 view | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect('admin/song/step-1/')->withErrors($exception->getMessage());
        }

    }

    public function step2Save(Request $request)
    {
        try {

            $songId = $request->song_id ? $request->song_id : null;
            /*if (\Session::has('create_song_id')) {
            $songId = \Session::get('create_song_id');
            } else {
            return view('errors.404');
            }*/

            // NEED TO SAVE CONTENT POLICIES
            if ($songId) {

                $song = Songs::where('songId', $songId)->first();
                $song->explicit = $request->explicit;
                if ($song->stage == 1) {
                    $song->stage = 2;
                }

                $song->advertisementPolicyId = $request->advertisement_policy;
                $song->save();

                $contentPolicies = $request->content_policies;

                $song->saveContentPolicy($contentPolicies);
                $this->solrController->kiki_song_delete_by_id($song->songId);
                $this->songSolr($song->songId);

                return redirect('admin/song/step-3/' . $songId);
            } else {
                return view('errors.404');
            }
        } catch (Exception $exception) {
            Log::error("Song step-2 save | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect('admin/song/step-1')->withErrors($exception->getMessage());
        }

    }

    public function step3View(Request $request, $id = null)
    {
        /*if (!Session::has('create_song_id')) {
        return view('errors.404');
        }*/

        try {
            $song = null;
            $image = [];
            $image_config = [];
            $track = [];
            $track_config = [];
            //if (Session::has('create_song_id') && Session::has('create_song_type')) {
            if ($id) {
                $songId = \Session::get('create_song_id');
                $type = \Session::get('create_song_type');

                $song = Songs::where('songId', $id)->first();

                if ($song->image) {
                    array_push($image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.song-image') . $song->image . "'>");
                    array_push($image_config, array(
                        'caption' => '',
                        'type' => 'image',
                        'key' => $song->songId,
                        'url' => url('admin/song/image-delete'),
                    ));
                }

                if ($song->track) {
                    $path = Config('constants.bucket2.url') . Config('filePaths.front.song-audio') . $song->track;
                    array_push($track, '<audio style="width: -webkit-fill-available;"  controls  src="' . $path . '">  Your browser does not support the
                <code>audio</code> element. </audio>');
                    array_push($track_config, array(
                        'caption' => '',
                        'type' => 'mp3',
                        'key' => $song->songId,
                        'url' => url('admin/song/audio-delete'),
                    ));
                }

                if ($song->durations) {
                    $time = explode(":", gmdate("i:s", $song->durations));
                    $song->duration_minutes = $time[0];
                    $song->duration_seconds = $time[1];
                } else {
                    $song->duration_minutes = 0;
                    $song->duration_seconds = 0;
                }

                return view('SongManage::step-3')
                    ->with([
                        'data' => $song,
                        'image' => $image,
                        'image_config' => $image_config,
                        'track' => $track,
                        'track_config' => $track_config,
                    ]);
            } else {
                return view('errors.404');
            }
        } catch (Exception $exception) {
            Log::error("Song step-3 view | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect('admin/song/step-1')->withErrors($exception->getMessage());
        }

    }

    public function step3Save(Request $request)
    {
        // return $request->all();
        // try {
            $songId = $request->song_id ? $request->song_id : null;
            /*if (\Session::has('create_song_id')) {
            $songId = \Session::get('create_song_id');
            } else {
            return view('errors.404');
            }*/
            if ($songId) {
                $song_image = $request->file('song_image');
                $song = Songs::where('songId', $songId)->first();
                $song_fileName = null;
                $path = null;
                if (File::exists($song_image)) {
                    $file = $song_image;
                    $extn = $file->getClientOriginalExtension();
                    $song_fileName = 'song-' . date('YmdHis') . '.' . $extn;
                    $path = $this->imageController->Upload('songs', $file, $song_fileName, $songId);
                    $song->image = $song_fileName;
                }

               
                $track_fileName = null;
                $trackPath = null;
                $track = $request->file('track');
                if (File::exists($track)) {
                    $track = $request->file('track');
                    $extn = $track->getClientOriginalExtension();
                    $track_fileName = $song->songId . '_song' . '.' . $extn;
                    $trackPath = $this->imageController->UploadAudio('kiki_music/mp3_files/', $track, $track_fileName, $songId);
                    $song->track = $track_fileName;
                    $songSmil = new SongSmil($song);
                    $songSmil->createSmil();

                }

                /*$songSmil = new SongSmil($song);
                $songSmil->createSmil();*/

                $songStage = $song->stage;
                if ($songStage == 2) {
                    $song->stage = 3;
                    $song->status = 1;
                }

                // $song->durations = ($request->duration_minutes * 60) + $request->duration_seconds;
                $song->durations = $request->duration_minutes;
                $song->save();

                if ($song) {
                    //IF Solr sync off not pushing (Only Live Enabled )
                    if (env('SOLR_SYNC')=='ON') {
                        $this->solrController->kiki_song_delete_by_id($song->songId);
                        $this->songSolr($song->songId);
                    }
                    
                    if ($songStage == 2 and $song->product) {
                        $requestType = '';
                        if(Request::exists('type')){
                            $typeValue = $request->get('type');
                            $requestType = "type=$typeValue";
                        }
                        return redirect('admin/products/' . $song->product . '/add/step-3?'.$requestType)->with(['success' => true,
                            'success.message' => 'Song Created successfully!',
                            'success.title' => 'Well Done!']);
                    } else {
                        return redirect('admin/song')->with(['success' => true,
                            'success.message' => 'Song Created successfully!',
                            'success.title' => 'Well Done!']);
                    }

                } else {
                    return redirect('admin/song')->with(['error' => true,
                        'error.message' => 'Song Creation Failed!',
                        'error.title' => 'Error!']);
                }
            } else {
                return view('errors.404');
            }
        // } catch (Exception $exception) {
        //     Log::error(" Song step-3 save| Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
        //     return redirect('admin/song')->withErrors($exception->getMessage());
        // }

    }

    public function listView()
    {
        return view('SongManage::list');
    }

    public function jsonList(Request $request)
    {

        try {

            $searchField = $request->get('field_name');
            $searchParam = $request->get('search_param');

            //return $searchParam.'---'.$searchField;

            $dataQuery = Songs::select([
                'songId',
                'name',
                'isbc_code',
                'publisherId',
                'categoryId',
                'track',
                'songs.status',
                'end_date'
            ])
                ->with([
                    'genres',
                    'category',
                    'primaryArtists',
                    'featuredArtists',
                    'products',
                    'publisher',
                    //'artists',
                ])
                // ->leftJoin('songs_categories', 'songs.categoryId', '=', 'songs_categories.categoryId')
                //->leftJoin('songs_publishers', 'songs.publisherId', '=', 'songs_publishers.publisherId')
            ;

            if ($searchField AND $searchParam) {
                switch ($searchField) {
                    case 'name':
                        $dataQuery->where('name', 'like', '%' . $searchParam . '%');
                        break;
                    case 'isrc':
                        $dataQuery->where('isbc_code', 'like', '%' . $searchParam . '%');
                        break;
                    case 'track':
                        $dataQuery->where('track', 'like', '%' . $searchParam . '%');
                        break;
                    case 'artist':
                        /*$dataQuery->whereHas('primaryArtists', function ($q) use ($searchParam) {
                            $q->where('name', 'like', '%' . $searchParam . '%');
                        })->orWhereHas('featuredArtists', function ($q) use ($searchParam) {
                            $q->where('name', 'like', '%' . $searchParam . '%');
                        });*/
                        $dataQuery->whereHas('artists', function ($q) use ($searchParam) {
                            $q->where('name', 'like', '%' . $searchParam . '%');
                        });
                        break;
                    case 'category':
                        $dataQuery->whereHas('category', function ($q) use ($searchParam) {
                            $q->where('name', 'like', '%' . $searchParam . '%');
                        });
                        break;
                    case 'genre':
                        $dataQuery->whereHas('genres', function ($q) use ($searchParam) {
                            $q->where('Name', 'like', '%' . $searchParam . '%');
                        });
                        break;
                    case 'publisher':
                        $dataQuery->whereHas('publisher', function ($q) use ($searchParam) {
                            $q->where('name', 'like', '%' . $searchParam . '%');
                        });
                        break;
                    case 'product':
                        $dataQuery->whereHas('products', function ($q) use ($searchParam) {
                            $q->where('name', 'like', '%' . $searchParam . '%');
                        });
                        break;
                    default:

                }
            }


            $dataTables = Datatables::eloquent($dataQuery)


                /*->addColumn('product_name', function ($dataQuery) {
                    $products = "";
                    foreach ($dataQuery->products as $product) {
                        $products .= $product->name . ", ";
                    }
                    return $products;
                })*/
                ->addColumn('product_name', function (Songs $songs) {
                    return $songs->products->map(function ($post) {
                        return $post->name;
                    })->implode(',<br>');
                })
                ->addColumn('artist_name', function ($value) {
                    $html = '';
                    $lastArtist = $value->primaryArtists->last();
                    foreach ($value->primaryArtists as $artist) {
                        $html .= "$artist->name";
                        if ($lastArtist->artistId != $artist->artistId) {
                            $html .= ",  ";
                        }

                    }
                    if ($value->featuredArtists->count() > 0) {
                        $html .= "<b> FT </b>";
                    }
                    $lastArtist = $value->featuredArtists->last();
                    foreach ($value->featuredArtists as $artist) {
                        $html .= "$artist->name";
                        if ($lastArtist->artistId != $artist->artistId) {
                            $html .= ",  ";
                        }

                    }
                    return $html;
                })
                /*->addColumn('artists', function (Songs $songs) {
                    return $songs->artists->map(function ($post) {
                        return $post->name;
                    })->implode(',<br>');
                })*/
                /*->addColumn('category', function (Songs $songs) {
                    return $songs->category->map(function($post) {
                        return $post->name;
                    })->implode('<br>');
                })*/
                ->addColumn('category_name', function ($value) {
                    return $value->category ? $value->category->name : '';
                })
                /*->addColumn('genres', function (Songs $songs) {
                    return $songs->genres->map(function ($post) {
                        return $post->Name;
                    })->implode(',<br>');
                })*/
                ->addColumn('genre_name', function ($value) {
                    $html = '';
                    foreach ($value->genres as $gens) {
                        $html .= "$gens->Name, ";
                    }
                    return $html;
                })
                ->addColumn('publisher_name', function ($value) {
                    return $value->publisher ? $value->publisher->name : '';
                })
                ->addColumn('track_download', function ($value) {
                    if ($value->track) {
                        $path = Config('constants.bucket2.url') . Config('filePaths.front.song-audio') . $value->track;
                        return '<center><a href="' . $path . '" download  target="_blank">' . $value->track . '</a></center>';
                    } else {
                        return '';
                    }
                })
//                ->editColumn('end_date', function ($value){
//
//                    $currentDate = strtotime(date('Y-m-d'));
//                    $endDate = null;
//                    if ($value->end_date) {
//                        $endDate = strtotime($value->end_date);
//                    }
//
//                    if(($endDate != null && $endDate != "") && $currentDate >= $endDate) {
//                        return '<label class="label label-danger">'.$value->end_date.'</label>';
//                    } else {
//                        return '<label>'.$value->end_date.'</label>';
//                    }
//
//                })
                ->editColumn('status', function ($value) {
                    return $value->status == 1 ? 'ACTIVE' : 'INACTIVE';
                })
                ->addColumn('action', function ($value) {
                    if ($value->status == 1) {
                        return '<center>
                                <a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $value->songId . '" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate">
                                <i class="fa fa-toggle-on"></i>
                                </a>
                                </center>';
                    } else {
                        return '<center>
                                <a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $value->songId . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate">
                                <i class="fa fa-toggle-off"></i>
                                </a>
                                </center>';
                    }
                })
                ->addColumn('edit', function ($value) {
                    return '<center><a href="#" class="blue" onclick="window.location.href=\'' . url('admin/song/step-1/' . $value->songId) . '\'" data-toggle="tooltip" data-placement="top" title="Edit Song"><i class="fa fa-pencil"></i></a></center>';
                });
            $dataTables->smart(false);

            //->toJson();
            return $dataTables->make(true);

        } catch (Exception $exception) {
            Log::error(" Song list view| Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return $exception->getMessage();
        }

        $dataQuery = Songs::with([
            'artist',
            'genre',
            'genres',
            'category',
            'primaryArtists',
            'featuredArtists',
            'products',
            'publisher',
        ]);

        $request->request->remove('artist_name');

        return Datatables::of($dataQuery)
            ->editColumn('products', function ($value) {
                $products = "";
                foreach ($value->products as $product) {
                    $products .= $product->name . ", ";
                }

                return $products;
            })
            ->addColumn('artist_name', function ($value) {
                $html = '';
                $lastArtist = $value->primaryArtists->last();
                foreach ($value->primaryArtists as $artist) {
                    $html .= "$artist->name";

                    if ($lastArtist->artistId != $artist->artistId) {
                        $html .= ",  ";
                    }

                }
                if ($value->featuredArtists->count() > 0) {
                    $html .= "<b> FT </b>";
                }
                $lastArtist = $value->featuredArtists->last();
                foreach ($value->featuredArtists as $artist) {
                    $html .= "$artist->name";
                    if ($lastArtist->artistId != $artist->artistId) {
                        $html .= ",  ";
                    }

                }
                return $html;
                // return $value->artist ? $value->artist->name : '';
            })
            ->addColumn('category_name', function ($value) {
                return $value->category ? $value->category->name : '';
            })
            ->addColumn('genre_name', function ($value) {
                $html = '';
                foreach ($value->genres as $gens) {
                    $html .= "$gens->Name, ";
                }
                return $html;
                //return $value->genre ? $value->genre->Name : '';
            })
            ->addColumn('publisher_name', function ($value) {
                return $value->publisher ? $value->publisher->name : '';
            })
            ->addColumn('track_download', function ($value) {
                if ($value->track) {
                    $path = Config('constants.bucket2.url') . Config('filePaths.front.song-audio') . $value->track;
                    return '<center><a href="' . $path . '" download  target="_blank">' . $value->track . '</a></center>';
                } else {
                    return '';
                }

            })
            //->filterColumn('artist_name', function ($query, $keyword) {
            // return $query;
            /*$query->whereHas('artist', function ($q) use ($keyword) {
            if ($keyword && $keyword != null) {
            $search = array_filter($keyword);
            if(count($search) > 0) {
            $q->whereIn('artist.name', $search);
            }
            }
            });*/
            // })
            ->filterColumn('category_name', function ($query, $keyword) {
            })
            ->filterColumn('genre_name', function ($query, $keyword) {
            })
            ->editColumn('status', function ($value) {
                return $value->status == 1 ? 'ACTIVE' : 'INACTIVE';
            })
            ->addColumn('action', function ($value) {
                if ($value->status == 1) {
                    return '<center><a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $value->songId . '" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';

                } else {
                    return '<center><a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $value->songId . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';

                }
            })
            ->addColumn('edit', function ($value) {
                return '<center><a href="#" class="blue" onclick="window.location.href=\'' . url('admin/song/step-1/' . $value->songId) . '\'" data-toggle="tooltip" data-placement="top" title="Edit Song"><i class="fa fa-pencil"></i></a></center>';
            })
            ->make(true);

        $limit = $request->input('length');
        $start = $request->input('start');

        $search = $request->input('search.value');

        $data = Songs::select('songId', 'name', 'isbc_code', 'artistId', 'categoryId', 'genreId', 'song_publisher',
            'release_date', 'product', 'status');

        if ($search) {
            $data = Songs::where(function ($q) use ($search) {
                $q->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('release_date', 'like', '%' . $search . '%')
                    ->orWhere('song_publisher', 'like', '%' . $search . '%')
                    ->orWhere(function ($artistQ) use ($search) {
                        $artistQ->whereHas('artist', function ($q1) use ($search) {
                            $q1->where('name', 'like', '%' . $search . '%');
                        });
                    })
                    ->orWhere(function ($catQ) use ($search) {
                        $catQ->whereHas('category', function ($q1) use ($search) {
                            $q1->where('name', 'like', '%' . $search . '%');
                        });
                    })
                    ->orWhere(function ($genreQ) use ($search) {
                        $genreQ->whereHas('genre', function ($q1) use ($search) {
                            $q1->where('name', 'like', '%' . $search . '%');
                        });
                    })
                    ->orWhere(function ($genreQ) use ($search) {
                        $genreQ->whereHas('genre', function ($q1) use ($search) {
                            $q1->where('name', 'like', '%' . $search . '%');
                        });
                    })
                    ->orWhere('isbc_code', 'like', '%' . $search . '%');
            });
        }

        $totalData = count($data->get());
        $totalFiltered = $totalData;

        $data = $data->orderBy('songId', 'desc')
            ->offset($start)->limit($limit)
            ->get();

        $jsonList = array();
        $i = 1;
        foreach ($data as $key => $song) {

            $dd = array();

            if ($song->songId != "") {
                array_push($dd, $song->songId);
            } else {
                array_push($dd, "-");
            }

            if ($song->name != "") {
                array_push($dd, $song->name);
            } else {
                array_push($dd, "-");
            }

            if ($song->isbc_code != "") {
                array_push($dd, $song->isbc_code);
            } else {
                array_push($dd, "-");
            }

            if (isset($song->artist)) {
                array_push($dd, $song->artist->name);
            } else {
                array_push($dd, "-");
            }

            if (isset($song->category)) {
                array_push($dd, $song->category->name);
            } else {
                array_push($dd, "-");
            }

            if (isset($song->genre)) {
                array_push($dd, $song->genre->Name);
            } else {
                array_push($dd, "-");
            }

            if ($song->song_publisher != "") {
                array_push($dd, $song->song_publisher);
            } else {
                array_push($dd, "-");
            }

            if ($song->product != "") {
                $arr = json_decode($song->product, true);
                $prod = "-";
                $products = Product::whereIn('id', $arr)->get();
                if ($products) {
                    foreach ($products as $product) {
                        if (!$prod) {
                            $prod = $product->name;
                        } else {
                            $prod .= ', ' . $product->name;
                        }
                    }
                }

                array_push($dd, $prod);
            } else {
                array_push($dd, "-");
            }

            if ($song->release_date != "") {
                array_push($dd, $song->release_date);
            } else {
                array_push($dd, "-");
            }

            $status = null;
            if ($song->status == 1) {
                $status = "ACTIVE";
            } else {
                $status = "INACTIVE";
            }

            array_push($dd, $status);

            if ($song->status == 0 || $song->status == 1) {

                $status = null;

                if ($song->status == 1) {
                    $checkbox = '<center><a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $song->songId . '" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                    array_push($dd, $checkbox);
                } else {
                    $checkbox = '<center><a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $song->songId . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                    array_push($dd, $checkbox);
                }

            } else {
                array_push($dd, "-");
            }

            array_push($dd, '<center><a href="#" class="blue" onclick="window.location.href=\'' . url('admin/song/edit/' . $song->songId) . '\'" data-toggle="tooltip" data-placement="top" title="Edit Song"><i class="fa fa-pencil"></i></a></center>');

            array_push($jsonList, $dd);
            $i++;
        }
        return Response::json(array(
            'data' => $jsonList,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
        ));

    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $state = $request->state;

        $song = Songs::where('songId', $id)->first();
        if ($song) {
            $song->status = $state;
            $song->save();
            $this->solrController->kiki_song_delete_by_id($song->songId);
            $this->songSolr($song->songId);
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }

    public function editSong($id)
    {
        Session::put('create_song_id', $id);
        Session::put('create_song_type', 'edit');

        return redirect('admin/song/step-1');

    }

    public function saveMoods($songId, $moods)
    {
        MoodSongs::where('song_id', $songId)->delete();

        $arr = [];
        foreach ($moods as $mood) {
            $arr[] = [
                'song_id' => $songId,
                'mood_id' => $mood,
            ];
        }

        MoodSongs::insert($arr);
    }

    public function saveProjects($songId, $projects)
    {
        SongProjects::where('song_id', $songId)->delete();

        $arr = [];
        foreach ($projects as $project) {
            $arr[] = [
                'song_id' => $songId,
                'project_id' => $project,
            ];
        }

        SongProjects::insert($arr);
    }

    public function saveProducts($songId, $products)
    {
        SongProducts::where('song_id', $songId)->delete();

        $arr = [];
        if($products){
            foreach ($products as $product) {
                $arr[] = [
                    'song_id' => $songId,
                    'product_id' => $product,
                ];
            }
            SongProducts::insert($arr);
        }

    }

    public function savePrimaryArtists($songId, $primaryArtists)
    {
        SongPrimaryArtists::where('song_id', $songId)->whereType('pa')->delete();

        $arr = [];
        if ($primaryArtists) {
            foreach ($primaryArtists as $artist) {
                if (is_numeric($artist)) {
                    $arr[] = [
                        'song_id' => $songId,
                        'artist_id' => $artist,
                        'type' => 'pa',
                    ];
                } else {
                    $newArtist = Artist::create(['name' => $artist, 'status' => 1]);
                    $arr[] = [
                        'song_id' => $songId,
                        'artist_id' => $newArtist->artistId,
                        'type' => 'pa',
                    ];
                }
            }

            SongPrimaryArtists::insert($arr);
        }
    }

    public function saveFeaturedArtists($songId, $featuredArtists)
    {
        SongPrimaryArtists::where('song_id', $songId)->whereType('fa')->delete();

        if ($featuredArtists) {
            $arr = [];
            foreach ($featuredArtists as $artist) {
                if (is_numeric($artist)) {
                    $arr[] = [
                        'song_id' => $songId,
                        'artist_id' => $artist,
                        'type' => 'fa',
                    ];
                } else {
                    $newArtist = Artist::create(['name' => $artist, 'status' => 1]);
                    $arr[] = [
                        'song_id' => $songId,
                        'artist_id' => $newArtist->artistId,
                        'type' => 'fa',
                    ];
                }

            }

            SongPrimaryArtists::insert($arr);
        }
    }

    public function saveLyricist($lyricist)
    {
        if ($lyricist) {
            if (!is_numeric($lyricist)) {
                $newLyricist = Lyricsts::create(['name' => $lyricist, 'status' => 1]);
                return $newLyricist->writerId;
            }

            return $lyricist;
        }
    }

    public function saveComposer($composer)
    {
        if ($composer) {
            if (!is_numeric($composer)) {
                $newComposer = SongComposer::create(['name' => $composer, 'status' => 1]);
                return $newComposer->id;
            }

            return $composer;
        }
    }

    public function saveGenres($songId, $genres)
    {
        SongGenres::where('song_id', $songId)->delete();

        $arr = [];
        foreach ($genres as $artist) {
            $arr[] = [
                'song_id' => $songId,
                'genre_id' => $artist,
            ];
        }

        SongGenres::insert($arr);
    }

    public function imageDelete(Request $request)
    {
        if ($request->has('key')) {
            $id = $request->get('key');
            $song = Songs::find($id);
            $song->image = null;
            $song->save();
            $this->solrController->kiki_song_delete_by_id($song->songId);
            $this->songSolr($song->songId);
            return 2;
        }
        return 1;
    }

    public function audioDelete(Request $request)
    {
        if ($request->has('key')) {
            $id = $request->get('key');
            $song = Songs::find($id);
            $song->track = null;
            $song->smilFile = null;
            $song->streamUrl = null;
            $song->save();
            $this->solrController->kiki_song_delete_by_id($song->songId);
            $this->songSolr($song->songId);
            return 2;
        }
        return 1;
    }

    public function searchPublisher(Request $request)
    {
        $search = $request->get('term');
        $publishers = [];
        if ($search) {
            $publishers = SongPublisher::where('name', 'like', '%' . $search . '%')
                ->where('status', 1)
                ->limit(20)
                ->orderBy('name', 'asc')
                ->get();
        }
        return $publishers;
    }


    private function songSolr($songId){
        try {
            $song = Songs::with([
                'mood', 'primaryArtists', 'featuredArtists', 'projects', 'products',
                'genres', 'writer', 'composer', 'publisher', 'subCategory'
            ])
                ->where('songId', $songId)
                ->first();
            if($song){
                $data = array(
                    'id' => $song->songId, //id is required
                    'Name' => $song->name,
                    'Description' => $song->description,
                    'ISRC Code' => $song->isbc_code,
                    'Primary Category' => $song->category ? $song->category->name : '',
                    'Sub Category' => $song->subCategory ? $song->subCategory->name : '',
                    //'Image URL' => $song->image ? Config('constants.bucket.url') . Config('filePaths.front.song-image') . $song->image : '',
                    'Image URL' => $song->image ?  $song->image : '',
                    'Song URL' => $song->streamUrl ? $song->streamUrl : '',
                    'Primary Artist' => $song->primaryArtists()->lists('name')->toArray(),
                    'Featured Artist' => $song->featuredArtists()->lists('name')->toArray(),
                    'Search Tags' => $song->search_tag,
                    'Mood' => $song->mood()->lists('name')->toArray(),
                    'Genre' => $song->genres()->lists('Name')->toArray(),
                    'Duration' => $song->durations ,
                    'Upload Date' => $song->uploaded_date ,
                    'End Date' => $song->end_date ,
                    'Release Date' => $song->release_date ,
                    'Status' => $song->status == 1 ? 'Active' : "Inactive",
                );
                $this->solrController->kiki_song_create_document($data);
            }
        }catch (Exception $exception){
            Log::error("song solr error ". $exception->getMessage());
        }
    }

    public function songSearch(Request $request){

        $search = $request->get('term');
        $songs = [];
        if($search){
            $songs =  Songs::where('name', 'like', '%' . $search . '%')->where('status', 1)->limit(20)->orderBy('name', 'asc')->get();
        }

        return $songs;

    }

}
