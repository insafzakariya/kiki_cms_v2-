<?php
namespace ProductManage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SolrController;
use ArtistManage\Models\Artist;
use Config;
use Datatables;
use DB;
use File;
use Illuminate\Http\Request;
use Log;
use ProductManage\Models\Product;
use ProductManage\Models\ProductArtists;
use ProjectManage\Models\Project;
use Response;
use Sentinel;
use Session;
use SongManage\Models\SongProducts;
use SongsCategory\Models\SongsCategory;
use Exception;

class ProductController extends Controller {

    private $productImagePath ;
    private $imageController;
    private $solrController;

    public function __construct()
    {
        $this->productImagePath = Config::get('filePaths.product-images');
        $this->imageController = new ImageController();
        $this->solrController = new SolrController();
    }

    public function index()
    {
        return view( 'ProductManage::list' );
    }

    public function getProducts(){
        try {
            $user = Sentinel::getUser();

            return Datatables::usingCollection(
                Product::select('products.id', 'products.name', 'products.description', 'products.status', 'products.type',
                    'products.artist_id')
                    ->with('artists')
                    ->get()
            )
                ->editColumn('status', function ($value){
                    return $value->status == 1 ? 'Activated' : 'Inactivated';
                })
                ->addColumn('artist', function ($value){
                    $artistNames = '';
                    foreach ($value->artists as $artist) {
                        if ($artistNames == '') {
                            $artistNames = $artist->name;
                        } else {
                            $artistNames = $artistNames . ", ". $artist->name;
                        }
                    }
                    return $artistNames;
                })
                ->addColumn('toggle-status', function ($value){
                    if($value->status == 1){
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue product-status-toggle " data-id="'.$value->id.'"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                    }else{
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue product-status-toggle " data-id="'.$value->id.'"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                    }
                })
                ->addColumn('edit', function ($value) use ($user){
                    if($user->hasAnyAccess(['admin.products.show', 'admin']))
                        return '<center><a href="#" class="blue" onclick="window.location.href=\''.url('admin/products/view/'.$value->id).'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Product"><i class="fa fa-pencil"></i></a></center>';
                })
                ->make(true);
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return Datatables::of(collect())->make(true);
        }
    }

    function create(){
        $artists = Artist::whereStatus(1)->get();
        $projects = Project::whereStatus(1)->get();
//        $songCats = SongsCategory::whereStatus(1)->get();
        $songCats = SongsCategory::with(['childs' => function ($query){
            $query->where('status', 1)->orderBy("name", "asc");
        }])
            /*->whereHas('childs', function ($query){
            $query->where('status', 1);
            })*/
            /*->where('childs.status', 1)*/
            ->whereStatus(1)
            ->where("parent_cat", 0)
            ->orderBy("name", "asc")
            ->get();
        return view('ProductManage::add', compact('artists', 'projects', 'songCats'));
    }

    function store(Request $request){
        try {
            $product = new Product;
            DB::transaction(function () use ($product, $request) {
                $product->name = $request->get('name');
                $product->description = $request->get('description');
                $product->status = 1;
                $product->artist_id = null;
                $product->project_id = $request->get('project');
                $product->type = $request->get('product_type');
                $product->upc_code = $request->get('upc_code');
                $product->project_category = $request->get('product_category');
                $product->step = "1";

                $product->save();
                $product->code = Product::$productCodePrefix . $product->id;
                $product->save();

                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'product-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $this->imageController->Upload($this->productImagePath, $aImage, $fileName, $product->id);

                $product->image = $fileName;
                $product->save();
//                $product->update([
//                    'image' => $fileName
//                ]);

                $prodArtists = [];

                foreach ($request->get('primary_artist') as $aritst) {
                    if (is_numeric($aritst)) {
                        $prodArtists [] = [
                            'artist_id' => $aritst,
                            'product_id' => $product->id
                        ];
                    } else {
                        $newArtist = Artist::create(['name' => $aritst, 'status' => 1]);
                        $prodArtists [] = [
                            'artist_id' => $newArtist->artistId,
                            'product_id' => $product->id
                        ];
                    }
                }

                ProductArtists::insert($prodArtists);

                $this->productSolr($product->id);

            });

//            Session::put('create_product_id', $product->id);

            return redirect('admin/products/'.$product->id.'/add/step-2?type=add');
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage(), $request->all());
            return redirect(route('admin.products.create'))->with([
                'error' => true,
                'error.message'=> 'Error adding new product. Please try again. Ex: '. $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function uploadImage($file, $path, $fileName){
        if (!file_exists($path)) {
            Log::info("Creating directory ".$path);
            File::makeDirectory($path, 0777, true);
        }

        $file->move($path, $fileName);
    }

    function show($products){
        try {
            $artists = Artist::whereStatus(1)->get();
            $projects = Project::whereStatus(1)->get();
//            $songCats = SongsCategory::whereStatus(1)->get();
            $songCats = SongsCategory::with(['childs' => function ($query){
                $query->where('status', 1)->orderBy("name", "asc");
            }])
               /* ->whereHas('childs', function ($query){
                $query->where('status', 1);
            })*/
                ->whereStatus(1)->where("parent_cat", 0)->orderBy("name", "asc")->get();
            $product = Product::with('projectCategory')->with('artists')->find($products);
            $image = [];
            $image_config = [];
            if($product->image){
                array_push($image, "<img style='height:190px' src='" .  Config('constants.bucket.url').Config('filePaths.front.product').$product->image . "'>");
                array_push($image_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => $product->id,
                    'url' => url('admin/products/image-delete'),
                ));
            }
            return view('ProductManage::edit', compact('product', 'artists', 'projects', 'songCats', 'image', 'image_config'));
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return redirect(route('admin.products.index'))->with([
                'error' => true,
                'error.message'=> "Please try again. Ex: ".$exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function showEdit($products){
        try {
            $type = "edit";
            $artists = Artist::whereStatus(1)->get();
            $projects = Project::whereStatus(1)->get();
//            $songCats = SongsCategory::whereStatus(1)->get();
            $songCats = SongsCategory::with(['childs' => function ($query){
                $query->where('status', 1)->orderBy("name", "asc");
            }])
               /* ->whereHas('childs', function ($query){
                $query->where('status', 1);
            })*/
                ->whereStatus(1)->where("parent_cat", 0)->orderBy("name", "asc")->get();
            $product = Product::with('projectCategory')->with('artists')->find($products);
            $image = [];
            $image_config = [];
            if($product->image){
                array_push($image, "<img style='height:190px' src='" .  Config('constants.bucket.url').Config('filePaths.front.product').$product->image . "'>");
                array_push($image_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => $product->id,
                    'url' => url('admin/products/image-delete'),
                ));
            }
            return view('ProductManage::edit', compact('product', 'artists', 'projects', 'songCats', 'image', 'image_config', 'type'));
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return redirect(route('admin.products.index'))->with([
                'error' => true,
                'error.message'=> "Please try again. Ex: ".$exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }


    function update(Request $request, $products){

        $type = $request->get('edit_table');

        try {
            $product = Product::find($products);
            $product->name = $request->get('name');
            $product->description = $request->get('description');
            $product->artist_id = null;
            $product->project_id = $request->get('project');
            $product->type = $request->get('product_type');
            $product->upc_code = $request->get('upc_code');
            $product->project_category = $request->get('product_category');
            if($request->hasFile('image')){
                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'product-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $this->imageController->Upload("product", $aImage, $fileName, $products);
                $product->image = $fileName;
            }

            $product->save();

            ProductArtists::where('product_id', $products)->delete();

            $prodArtists = [];

            foreach ($request->get('primary_artist') as $aritst) {
                if (is_numeric($aritst)) {
                    $prodArtists [] = [
                        'artist_id' => $aritst,
                        'product_id' => $product->id
                    ];
                } else {
                    $newArtist = Artist::create(['name' => $aritst, 'status' => 1]);
                    $prodArtists [] = [
                        'artist_id' => $newArtist->artistId,
                        'product_id' => $product->id
                    ];
                }
            }

            ProductArtists::insert($prodArtists);

            $this->solrController->kiki_product_delete_by_id($product->id);
            $this->productSolr($product->id);

//            return redirect(route('admin.products.index'))->with(['success' => true,
//                'success.message' => "Successfully updated product",
//                'success.title' => 'Success']);

//            Session::put('create_product_id', $product->id);

            if ($type != null && $type == "edit") {
                return redirect('admin/products/'.$product->id.'/add/step-3?type='.$type);
            } else {
                return redirect('admin/products/'.$product->id.'/add/step-2?type=add');
            }
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage(), $request->all(), array_push($request->all(), $products));
            return redirect(route('admin.products.index', $products))->with([
                'error' => true,
                'error.message'=> 'Error updating product. Please try again. Ex: '. $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function toggleStatus($products){
        try {
            $product = Product::find($products);
            $product->status = $product->status == 1 ? 0 : 1;
            $product->save();
            $this->solrController->kiki_product_delete_by_id($product->id);
            $this->productSolr($product->id);
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return response()->json("error : ". $exceptionId, 403);
        }
    }


    function step2View($id)
    {

//        $productId = null;
//        if (Session::has('create_product_id')) {
//            $productId = \Session::get('create_product_id');
//        }

        $product = Product::with('primaryArtist')->where('id', $id)->first();
        if (!$product) {
            return view('errors.404');
        }
        $artistName = null;
        foreach ($product->artists as $artist) {
            if ($artistName == null) {
                $artistName = $artist->name;
            } else {
                $artistName .= ", ". $artist->name;
            }
        }
        $product->artistNames = $artistName;

        return view('ProductManage::step-2')->with('product', $product);
    }

    function songAddView($id, Request $request)
    {
//        if (!Session::has('create_product_id')) {
        if (!$id) {
            return view('errors.404');
        }

        $type = isset($request->type) ? $request->type : null;
        if ($type == "edit") {
            $url = url("/admin/products/$id/add/step-3").'?type=edit';
        } else {
            $url = url("/admin/products/$id/add/step-2").'?type=add';
        }

        return view('ProductManage::song-add')->with(['id' => $id, 'url' => $url]);
    }

    public function redirectToStep3()
    {
        return redirect('admin/products/add/step-3');
    }

    function step2Save(Request $request)
    {
        $productId = $request->product_id;
//        if (Session::has('create_product_id')) {
//            $productId = \Session::get('create_product_id');
//        } else {
//            return view('errors.404');
//        }

        $songs = $request->songs;

        $data = array();

        foreach ($songs as $key => $song) {
            $data [] = [
                'product_id' => $productId,
                'song_id' => $song,
                'song_order' => ($key+1),
            ];
        }

        $songProducts = SongProducts::insert($data);

        $product = Product::find($productId);
        $product->step = "2";
        $product->save();

        if ($songProducts == 1) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }

    function step3View($id, Request $request)
    {
//        $productId = null;
//        if (!Session::has('create_product_id')) {
//            return view('errors.404');
//        }

//        $productId = Session::get('create_product_id');

        if (!$id) {
            return view('errors.404');
        }

        $type = isset($request->type) ? $request->type : "add";

        return view('ProductManage::step-3')->with(['product_id' => $id, 'type' => $type]);
    }

    function getSongsOfProduct(Request $request, $id)
    {
        $limit = $request->input('length');
        $start = $request->input('start');
        $search = $request->input('search.value');

//        $productId = null;
//        if (\Session::has('create_product_id')) {
//            $productId = \Session::get('create_product_id');
//        } else {
//            return view('errors.404');
//        }

        if (!$id) {
            return view('errors.404');
        }

        $data = null;

        if (!$search) {
            $data = SongProducts::select('id', 'product_id', 'song_id')
                ->where('product_id', $id);
        } else {
            $data = SongProducts::select('id', 'product_id', 'song_id');

            $data = $data->whereHas('song', function ($q) use ($search){
                $q->where(function ($q) use ($search){
                    $q->where('name', 'like', '%'.$search.'%')
                        ->orWhere('isbc_code', 'like', '%'.$search.'%');
                });
            })->orWhereHas('song.primaryArtists', function ($q) use ($search){
                $q->where('name', 'like', '%'.$search.'%');
            })->where('product_id', $id);
        }

        $totalData = count($data->get());
        $totalFiltered = $totalData;

        $data = $data->orderBy('song_order', 'asc')
            ->offset($start)->limit($limit)
            ->get();


        $jsonList = array();
        $i=1;
        foreach ($data as $key => $song) {

            $artists =  "";
            if($song->song->primaryArtists){
                foreach($song->song->primaryArtists as $artist){
                    if ($artists == "") {
                        $artists = $artist->name;
                    } else {
                        $artists .= ", ".$artist->name;
                    }
                }
            }

            if($song->song->featuredArtists){
                if ($artists != "" && count($song->song->featuredArtists) > 0) {
                    $artists .= " ft ";
                }
                foreach($song->song->featuredArtists as $artist){
                    if ($artists == "") {
                        $artists = $artist->name;
                    } else {
                        $artists .= ", ".$artist->name;
                    }
                }
            }

            $proTypes = "";
            if($song->song->products){
                foreach($song->song->products as $product){
                    $proTypes .= $product->type.", ";
                }
            }

            $genres = "";
            if($song->song->genres){
                foreach($song->song->genres as $genre){
                    if ($genres == "") {
                        $genres = $genre->Name;
                    } else {
                        $genres .= ", ".$genre->Name;
                    }
                }
            }

            $dd = array(
                'id' => $song->song_id,
                'name' => $song->song->name != "" ? $song->song->name : "-",
                'genre_name' => $genres,
                'artist_name' => $artists,
                'isrc_code' => $song->song->isbc_code,
                'category_name' => $song->song->category->name != "" ? $song->song->category->name : "-",
                'writer_name' => $song->song->writer->name != "" ? $song->song->writer->name : "-",
                'music_by' => $song->song->composer ? $song->song->composer->name : "-",
                'action' => "<center><a href='#' class='blue' bid='{$song->id}' onclick='confirmAlert($song->id)' data-toggle=\"tooltip\" data-placement=\"top\" title=\"Remove Song\"><i class=\"fa fa-trash\"></i></a></center>",
            );

            array_push($jsonList, $dd);
            $i++;
        }
        return Response::json(array(
            'data' => $jsonList,
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered)
        ));
    }

    function orderSongs(Request $request){
        try {
            DB::transaction(function () use ($request){
                foreach ($request->get('order') as $songOrder){
                    $song = SongProducts::where('product_id', $request->get('product_id'))
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

    function step3Save(Request $request)
    {

        $productId = $request->id;

        $product = Product::find($productId);
        $product->step = "3";
        $product->save();
        $this->solrController->kiki_product_delete_by_id($product->id);
        $this->productSolr($product->id);

        return redirect(route('admin.products.index'))->with(['success' => true,
            'success.message' => "Successfully created product",
            'success.title' => 'Success']);
    }

    public function removeSongFromProduct(Request $request)
    {
        $song = SongProducts::find($request->id);
        if ($song) {
            $song->delete();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }
    private function productSolr($productId)
    {
        try {
            $product = Product::with('projectCategory')->find($productId);
            if ($product) {
                $data = array(
                    'id' => $productId, //id is required
                    'Name' => $product->name,
                    'Type' => $product->type,
                    'Description' => $product->description,
                    'Primary Artist' => $product->artists()->lists('name')->toArray(),
                    //'Image URL' => $product->image ? Config('constants.bucket.url') . Config('filePaths.front.product') . $product->image : '',
                    'Image URL' => $product->image ?  $product->image : '',
                    'Primary Category' => $product->projectCategory ? $product->projectCategory->name : '',
                    'Status' => $product->status == 1 ? 'Active' : "Inactive"
                );
                $this->solrController->kiki_product_create_document($data);
            }
        } catch (Exception $exception) {
            Log::error("product solr error " . $exception->getMessage());
        }
    }
}
