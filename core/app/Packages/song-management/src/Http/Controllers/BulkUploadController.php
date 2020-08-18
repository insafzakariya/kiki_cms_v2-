<?php


namespace SongManage\Http\Controllers;


use App\Classes\SongSmil;
use App\Http\Controllers\Controller;
use App\Models\Policy;
use ArtistManage\Models\Artist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use LyricistManage\Models\Lyricist;
use Maatwebsite\Excel\Facades\Excel;
use File;
use Log;
use DB;
use MoodManage\Models\Mood;
use PlaylistManage\Models\AudioGenre;
use ProductManage\Models\Product;
use ProjectManage\Models\Project;
use SongComposerManage\Models\SongComposer;
use SongManage\Models\MoodSongs;
use SongManage\Models\SongBulkUpload;
use SongManage\Models\SongGenres;
use SongManage\Models\SongPrimaryArtists;
use SongManage\Models\SongProducts;
use SongManage\Models\SongProjects;
use SongManage\Models\SongPublisher;
use SongManage\Models\Songs;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use SongsCategory\Models\SongsCategory;

class BulkUploadController extends Controller
{

    public function songUpload()
    {

        try {
            return view('SongManage::bulk-upload')
                ->with([

                ]);
        } catch (Exception $exception) {
            Log::error("Song bulk upload | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return view('errors.404');
        }

    }

    public function upload(Request $request)
    {

        try {
            DB::beginTransaction();


            $file = $request->file('upload_file');
            if (File::exists($file)) {
                $path = $file->getRealPath();
                $songData = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                })->setDateColumns(['end_date', 'release_date', 'uploaded_date'])->get();
              //  return response($songData, 200);
                $row = 0;
                $errors = [];
                $data = [];
                foreach ($songData as $key => $rowData) {
                    if (!$rowData->filter()->isEmpty()) {
                        //foreach ($songData as $rowData) {
                        // return response($rowData, 200);
                        $row++;
                        /*if ($data[2] == '' && $data[3] == '' && $data[5] == '' && $data[4] == '') {
                            break;
                        } */
                        //primary_artist
                        $primaryArtist = $rowData['primary_artist'];
                        $primaryArtists = array();
                        if ($primaryArtist) {
                            $myArray = explode(',', $primaryArtist);
                            foreach ($myArray as $artist) {
                                array_push($primaryArtists, trim($artist));
                            }
                        }
                        $featuredArtist = $rowData['featured_artist'];
                        $featuredArtists = array();
                        if ($featuredArtist) {
                            $myArray = explode(',', $featuredArtist);
                            foreach ($myArray as $item) {
                                array_push($featuredArtists, trim($item));
                            }
                        }

                        /**
                         * create mood array with comma
                         */
                        $mood = $rowData['mood'];
                        $moods = array();
                        if ($mood) {
                            $myArray = explode(',', $mood);
                            foreach ($myArray as $item) {
                                array_push($moods, trim($item));
                            }
                            $moods = array_unique($moods);
                        }

                        $songGenre = $rowData['song_genre'];
                        $songGenres = array();
                        if ($songGenre) {
                            $myArray = explode(',', $songGenre);
                            foreach ($myArray as $item) {
                                array_push($songGenres, trim($item));
                            }
                            $songGenres = array_unique($songGenres);
                        }

                        $project = $rowData['project'];
                        $projects = array();
                        if ($project) {
                            $myArray = explode(',', $project);
                            foreach ($myArray as $item) {
                                array_push($projects, trim($item));
                            }
                            $projects = array_unique($projects);
                        }

                        $product = $rowData['product'];
                        $products = array();
                        if ($product) {
                            $myArray = explode(',', $product);
                            foreach ($myArray as $item) {
                                array_push($products, trim($item));
                            }
                            $products = array_unique($products);
                        }

                        $content = $rowData['content_policy'];
                        $contents = array();
                        if ($content) {
                            $myArray = explode(',', $content);
                            foreach ($myArray as $item) {
                                array_push($contents, trim($item));
                            }
                            $contents = array_unique($contents);
                        }

                        //return response($moods, 200);
                        $end_date = $rowData['end_date'] ? Carbon::parse($rowData['end_date'])->format('Y-m-d') : '';
                        $release_date = $rowData['release_date'] ? Carbon::parse($rowData['release_date'])->format('Y-m-d') : '';
                        $uploaded_date = $rowData['uploaded_date'] ? Carbon::parse($rowData['uploaded_date'])->format('Y-m-d') : '';

                        // return;
                        //return response($end_date, 200);

                        $categoryId = null;
                        $category = $rowData['category'];
                        $publisher = $rowData['publisher'];

                        /**
                         * if not exist we need to create new one
                         */
                        $composer = $rowData['composer'];
                        $lyricist = $rowData['lyricist'];
                        /*$productId = null;
                        $productName = $rowData['category'];
                        if ($productName) {
                            $product = Product::where('name', $categoryName)->first();
                            if($product){
                                $lyricsId = $product->categoryId;

                            }
                        }*/
                        /**
                         * make search tags to array and remove duplicate
                         */
                        $search_tag = array();
                        $tags = $rowData['search_tags'];
                        if ($tags) {
                            foreach (explode(',', $tags) as $tag)
                                array_push($search_tag, $tag);
                            $search_tag = array_unique($search_tag);
                        }
                        $newData = [
                            'name' => $rowData['song_name'],
                            'isbc_code' => $rowData['isrc_code'],
                            'description' => $rowData['description'],
                            'status' => 0,
                            'line' => $rowData['line'],
                            'release_date' => $release_date,
                            'uploaded_date' => $uploaded_date,
                            'end_date' => $end_date,
                            'durations' => $rowData['duration'],
                            'search_tag' => $search_tag,
                            'category' => $category,
                            'sub_categories' =>$rowData['sub_category'],
                            'writer' => $lyricist,
                            'publisher' => $publisher,
                            'moods' => $moods,
                            'song_genres' => $songGenres,
                            //'product' =>$,
                            'music' => $composer,
                            'primary_artists' => $primaryArtists,
                            'featured_artists' => $featuredArtists,
                            'products' => $products,
                            'projects' => $projects,
                            'advertisement_policy' => $rowData['advertisement_policy'],
                            'contents' => $contents,
                            'explicit' => $rowData['explicit'],
                            'image' => $rowData['image'],
                           // 'smilFile' => $rowData['smilfile'],
                           // 'streamUrl' => $rowData['streamurl'],
                        ];
                        $messages = [
                            'name.required' => "Song Name field is required. Row No-{$row} ",
                            'image.required' => "Song Image field is required. Row No-{$row} ",
                            'isbc_code.required' => "Code field is required. Row No-{$row} ",
                            'description.required' => "Description field is required. Row No-{$row} ",
                            'line.required' => "Line field is required. Row No-{$row} ",
                            'line.integer' => "Line field should be number. Row No-{$row} ",
                            'release_date.required' => "Release date field is required. Row No-{$row} ",
                            'release_date.date' => "Please check release date format. Row No-{$row} ",
                            'uploaded_date.required' => "Uploaded date field is required . Row No-{$row} ",
                            'uploaded_date.date' => "Please check uploaded date required. Row No-{$row} ",
                            'end_date.required' => "End date field is required. Row No-{$row} ",
                            'end_date.date' => "Please check end date required. Row No-{$row} ",
                            'durations.required' => "Duration field is required. Row No-{$row} ",
                            'durations.integer' => "Line field should be number. Row No-{$row} ",
                            'category.required' => "Category field is required. Row No-{$row} ",
                            'sub_categories.required' => "Sub Category field is required. Row No-{$row} ",
                            'category.exists' => "Given category not exist in database. Row No-{$row} ",
                            'sub_categories.exists' => "Given Sub category not exist in database. Row No-{$row} ",
                            'writer.required' => "Lyricist field is required. Row No-{$row} ",
                            'publisher.required' => "Publisher field is required. Row No-{$row} ",
                            'publisher.exists' => "Given publisher not exist in database. Row No-{$row} ",
                            'music.required' => "Composer field is required. Row No-{$row} ",
                            'music.exists' => "Given music not exist in database. Row No-{$row} ",
                            'primary_artists.required' => "Primary Artist field is required. Row No-{$row} ",
                            'primary_artists.min' => "Primary Artist field is required. Row No-{$row} ",
                            'projects.required' => "Projects field is required. Row No-{$row} ",
                            'projects.min' => "Projects field is required. Row No-{$row} ",
                            'products.required' => "Products field is required. Row No-{$row} ",
                            'products.min' => "Products field is required. Row No-{$row} ",
                            'contents.required' => "contents field is required. Row No-{$row} ",
                            'contents.min' => "contents field is required. Row No-{$row} ",
                            'moods.required' => "moods field is required. Row No-{$row} ",
                            'moods.min' => "moods field is required. Row No-{$row} ",
                            'song_genres.required' => "Genre field is required. Row No-{$row} ",
                            'song_genres.min' => "Genre field is required. Row No-{$row} ",
                            'explicit.required' => "Explicit field is required. Row No-{$row} ",
                            'explicit.in' => "Explicit should be one of (no, yes, clean). Row No-{$row} ",
                        ];
                        $rules = [
                            'name' => 'required',
                            'image' => 'required',
                            'isbc_code' => 'required',
                            'description' => 'required',
                            'line' => 'required|integer',
                            'release_date' => 'required|date',
                            'uploaded_date' => 'required|date',
                            'end_date' => 'required|date',
                            'durations' => 'required|integer',
                            'category' => 'required|exists:songs_categories,name,status,1',
                            'sub_categories' => 'exists:songs_categories,name,status,1',
                            'publisher' => 'required|exists:songs_publishers,name,status,1',
                            'advertisement_policy' => 'required|exists:policies,PolicyID,PolicyType,6',
                            // 'moods.*' => 'required|exists:moods,name,status,1',
                            //  'music' => 'required|exists:song_composers,name,status,1',
                            'music' => 'required',
                            'writer' => 'required',
                            'explicit' => 'required|in:no,clean,yes',
                            'primary_artists' => 'required|array|min:1',
                            'projects' => 'required|array|min:1',
                            'products' => 'required|array|min:1',
                            'contents' => 'required|array|min:1',
                            'moods' => 'required|array|min:1',
                            'song_genres' => 'required|array|min:1',
                        ];

                        foreach($products as $key => $val)
                        {
                            $rules = array_merge($rules, [
                                "products.$key" => 'exists:products,name,status,1'
                            ]);
                            $messages =array_merge($messages, [
                                "products.$key.exists" => "Product name - '$val' is not exist in database. Row No-{$row} "
                            ]);

                        }

                        foreach($projects as $key => $val)
                        {
                            $rules = array_merge($rules, [
                                "projects.$key" => 'exists:projects,name,status,1'
                            ]);
                            $messages =array_merge($messages, [
                                "projects.$key.exists" => "project  name - '$val' is not exist in database. Row No-{$row} "
                            ]);
                        }
                        foreach($contents as $key => $val)
                        {
                            $rules = array_merge($rules, [
                                "contents.$key" => 'exists:policies,PolicyID,PolicyType,10'
                            ]);
                            $messages =array_merge($messages, [
                                "contents.$key.exists" => "Content Policy  ID - '$val' is not exist in database. Row No-{$row} "
                            ]);
                        }

                        foreach($moods as $key => $val)
                        {
                            $rules = array_merge($rules, [
                                "moods.$key" => 'exists:moods,name,status,1'
                            ]);
                            $messages =array_merge($messages, [
                                "moods.$key.exists" => "Moods  name - '$val' is not exist in database. Row No-{$row} "
                            ]);
                        }

                        foreach($songGenres as $key => $val)
                        {
                            $rules = array_merge($rules, [
                                "song_genres.$key" => 'exists:audio_genre,Name,Status,1'
                            ]);
                            $messages =array_merge($messages, [
                                "song_genres.$key.exists" => "Genre  name - '$val' is not exist in database. Row No-{$row} "
                            ]);
                        }

                        //return $rules;

                        $validator = Validator::make($newData, $rules , $messages);
                        if ($validator->fails()) {
                            return response()->json([
                                'errors' => $validator->errors(),
                            ],
                                422
                            );
                        }
                        array_push($data, $newData);
                    }
                }
              //  return response($data, 200);
                $startSongId = null;
                if($data){
                    $user = Sentinel::getUser();
                    $bulkUpload = SongBulkUpload::create([
                        'date' => $request->get('upload_date'),
                        'row_count' => $row,
                        'user_id' => $user->id,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                    //  $songs = [];
                    foreach ($data   as $key => $row){
                        if ($row['category']) {
                            $category = SongsCategory::where('name', $row['category'])->whereStatus(1)->first();
                            if($category){
                                $data[$key]['categoryId'] = $category->categoryId;
                            }
                        }
                        if ($row['sub_categories']) {
                            $category = SongsCategory::where('name', $row['sub_categories'])->whereStatus(1)->first();
                            if($category){
                                $data[$key]['sub_categories'] = $category->categoryId;
                            }
                        }

                        if ($row['publisher']) {
                            $publisher = SongPublisher::where('name', $row['publisher'])->whereStatus(1)->first();
                            if($publisher){
                                $data[$key]['publisherId'] = $publisher->publisherId;
                            }
                        }
                        //select or create
                        if ($row['music']) {
                            $composer = SongComposer::firstOrCreate([
                                    'name' => $row['music'],
                                    'status' => 1]
                            );
                            if($composer){
                                $data[$key]['musicId'] = $composer->id;
                            }
                        }
                        // select or create
                        if ($row['writer']) {
                            $lyrics = Lyricist::firstOrCreate([
                                'name' =>  $row['writer'],
                                'status' => 1
                            ]);
                            if($lyrics){
                                $data[$key]['lyricsId'] = $lyrics->writerId;
                            }
                        }

                        /**
                         * passing Id so no need to check name
                         */
                        if ($row['advertisement_policy']) {
                            $policy = Policy::where('PolicyID', $row['advertisement_policy'])->where('PolicyType', 6)->first();
                            if($policy){
                                $data[$key]['advertisementPolicyId'] = $policy->PolicyID;
                            }
                        }
                        //  return $data[$key]['advertisementPolicyId'];
                        // $newSong = Songs::create($row);
                        $song = Songs::create([
                            'name' => $row['name'],
                            'isbc_code' => $row['isbc_code'],
                            'description' => $row['description'],
                            'search_tag' => $row['search_tag'],
                            'status' => 1,
                            'stage' => null,
                            'artistId' => null,
                            'featured_artists' => null,
                            'categoryId' => $data[$key]['categoryId'],
                            'sub_categories' =>$data[$key]['sub_categories'],
                           // 'moods' => null,
                            'genreId' => null,
                            'writerId' => $data[$key]['lyricsId'],
                            'publisherId' => $data[$key]['publisherId'],
                            'project' => null,
                            //'product' => $row->productId,
                            'line' => $row['line'],
                            'release_date' => $row['release_date'],
                            'uploaded_date' => $row['uploaded_date'],
                            'end_date' => $row['end_date'],
                            'musicId' => $data[$key]['musicId'],
                            'durations' =>  $row['durations'],
                            'advertisementPolicyId' => $data[$key]['advertisementPolicyId'] ,//(int)$row['advertisement_policy'],
                            'explicit' => $data[$key]['explicit'],
                            'song_bulk_upload_id' => $bulkUpload->id,
                            'image' => $row['image'],
                        ]);
                        $songId = $song->songId;

                        $this->saveArtists($songId, $row['primary_artists'], 'pa');
                        $this->saveArtists($songId, $row['featured_artists'], 'fa');
                        $this->saveProjects($songId, $row['projects']);
                        $this->saveProducts($songId, $row['products']);
                        $this->saveMoods($songId, $row['moods']);
                        $this->saveGenres($songId, $row['song_genres']);
                        $song->saveContentPolicy($row['contents']);
                        $songSmil = new SongSmil($song);
                        $songSmil->createSmil();

                        if($key == 0) {
                            $startSongId = $songId;
                        }
                        /*$songs = Songs::where('songId', $song->songId)
                            ->with(['contentPolicies',
                                'mood',
                                'primaryArtists',
                                'featuredArtists',
                                'projects',
                                'products',
                                'genres',
                                'writer',
                                'composer',
                                'category',
                                'music',
                                'publisher'])
                            ->first();
                        break;*/
                    }

                    $bulkUpload->update([
                        'start' => $startSongId,
                        'end' => $song->songId,
                    ]);
                }

                //return response($data, 200);
                DB::commit();
               // return response($songs, 200);
                return response()->json([
                    'message' => 'updated',
                ],
                    200
                );

            } else {
                Log::error("Song bulk upload | No file");
                return response()->json([
                    'message' => 'No file',
                ],
                    404
                );
            }

        } catch (Exception $exception) {
            DB::rollback();
            Log::error("Song bulk upload | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return response()->json([
                'message' => 'Something went wrong',
            ],
                500
            );
        }
    }

    /**
     * @param $songId int
     * @param $primaryArtists array
     * @param $type string
     */
    public function saveArtists($songId, $primaryArtists, $type)
    {
        SongPrimaryArtists::where('song_id', $songId)->whereType($type)->delete();
        $arr = [];
        if ($primaryArtists) {
            foreach ($primaryArtists as $artist) {
                $artist = Artist::firstOrCreate(['name' => $artist, 'status' => 1]);
                $arr[] = [
                    'song_id' => $songId,
                    'artist_id' => $artist->artistId,
                    'type' => $type,
                ];
                /*if ($artist) {
                    $arr[] = [
                        'song_id' => $songId,
                        'artist_id' => $artist->artistId,
                        //'type' => 'pa',
                        'type' => $type,
                    ];
                } else {
                    $newArtist = Artist::create(['name' => $artist, 'status' => 1]);
                    $arr[] = [
                        'song_id' => $songId,
                        'artist_id' => $newArtist->artistId,
                        //'type' => 'pa',
                        'type' => $type,
                    ];
                }*/
            }
            SongPrimaryArtists::insert($arr);
        }
    }

    /**
     * @param $songId int
     * @param $projects array
     */
    public function saveProjects($songId, $projects)
    {
        $arr = [];
        foreach ($projects as $project) {
            $projectData = Project::where('name', $project)->first();
            if ($projectData) {
                $arr[] = [
                    'song_id' => $songId,
                    'project_id' => $projectData->id,
            ];
            }
        }

        SongProjects::insert($arr);
    }

    /**
     * @param $songId
     * @param $products
     */
    public function saveProducts($songId, $products)
    {
        $arr = [];
        foreach ($products as $product) {
            $productData = Product::where('name', $product)->first();
            if ($productData) {
                $arr[] = [
                    'song_id' => $songId,
                    'product_id' => $productData->id,
                ];
            }
        }

        SongProducts::insert($arr);
    }

    /**
     * @param $songId
     * @param $moods
     */
    public function saveMoods($songId, $moods)
    {
        $arr = [];
        foreach ($moods as $mood) {
            $moodData = Mood::where('name', $mood)->first();
            $arr[] = [
                'song_id' => $songId,
                'mood_id' => $moodData->id,
            ];
        }

        MoodSongs::insert($arr);
    }

    /**
     * @param $songId
     * @param $genres
     */
    public function saveGenres($songId, $genres)
    {
        $arr = [];
        foreach ($genres as $item) {
            $geresData = AudioGenre::where('Name', $item)->where('Status', 1)->first();
            $arr[] = [
                'song_id' => $songId,
                'genre_id' => $geresData->GenreID,
            ];
        }

        SongGenres::insert($arr);
    }
}