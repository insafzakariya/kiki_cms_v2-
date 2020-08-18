<?php

namespace SongsCategory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use Config;
use File;
use Illuminate\Http\Request;
use Log;
use Redirect;
use Sentinel;
use SongsCategory\Models\SongsCategory;

class SongsCategoryController extends Controller
{
    /**
     * @var string
     */
    private $songCategoryImagePath;

    /**
     * @var ImageController
     */
    private $imageController;

    /**
     * Description
     *
     * @var string
     */
    public function __construct()
    {
        $this->songCategoryImagePath = Config::get('filePaths.song-category-image');
        $this->imageController = new ImageController();
    }

    /**
     * Description
     *
     * @return view
     */
    public function index()
    {
        $categories = SongsCategory::where('parent_cat', '=', 0)->get();
        $allCategories = SongsCategory::all();
        return view('SongsCategory::index', compact('categories', 'allCategories'));
    }

    /**
     * Description: Insert category to DB
     *
     * @param Request $request Comment about this variable
     *
     * @return Redirect
     */
    public function create()
    {
        $categories = SongsCategory::all();
        return view('SongsCategory::add', compact('categories'));
    }

    /**
     * Description: Insert category to DB
     *
     * @return Redirect
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'cat_name'=> 'required',
                'parent_cat'=> 'required'
            ]
        );

        $fileName = '';
        if ($request->file('image')) {
            $aImage = $request->file('image');
            $ext = $aImage->getClientOriginalExtension();
            $fileName = 'Category-'.rand(0, 999999).'-'. date('YmdHis') . '.' . $ext;
            $path = $this->imageController->Upload($this->songCategoryImagePath, $aImage, $fileName, "-")."/".$fileName;
        }

        $parentCat = $request->parent_cat;
        if ($request->parent_cat === 'main') {
            $parentCat = 0;
        } else if ($request->parent_cat === "0") {
            return Redirect::route('songs-category.create')->withErrors('Select Parent')->withInput();
        }

        $category = new SongsCategory();
        $category->name = $request->cat_name;
        $category->description = $request->description;
        $category->parent_cat = $parentCat;
        $category->image = $fileName;
        $category->search_tag = $request->cat_tags;
        $category->status = 1;
        $category->save();

        if ($category) {
            return Redirect::route('songs-category.index')->with('msg', 'Category successfull added!');
        } else {
            return Redirect::route('songs-category.create')->withError('Category add unsuccessful!');
        }
    }

    /**
     * Description: save category image in server
     *
     * @param $file
     * @param $path
     * @param $fileName
     *
     * @return void
     */
    private function uploadImage($file, $path, $fileName)
    {
        if (!file_exists($path)) {
            Log::info("Creating directory ".$path);
            File::makeDirectory($path, 0777, true);
        }

        $file->move($path, $fileName);
    }

    /**
     * Description
     *
     * @return view
     */
    public function edit(Request $request)
    {
        $categories = SongsCategory::all();
        $catDetails = SongsCategory::where('categoryId', $request->id)->first();
        $image = [];
        $image_config = [];
        if($catDetails->image){
            array_push($image, "<img style='height:190px' src='" .  Config('constants.bucket.url').Config('filePaths.front.song-category').$catDetails->image . "'>");
            array_push($image_config, array(
                'caption' => '',
                'type' => 'image',
                'key' => $catDetails->categoryId,
                'url' => url('admin/songs-category/image-delete'),
            ));
        }

        return view('SongsCategory::edit', compact ('catDetails', 'categories', 'image', 'image_config'));
    }

    /**
     * Description: Insert category to DB
     *
     * @return Redirect
     */
    public function update(Request $request, $catId)
    {
        try{
            $category = SongsCategory::find($catId);
            $parentCatId =$request->parent_cat;
            if ($request->parent_cat === 'main') {
                $parentCatId = 0;
            }else{
                $parentCat = SongsCategory::find($request->parent_cat);
                if($parentCat && $category->status != 0){
                    $category->status = $parentCat->status;
                }
            }

            $category->name = $request->cat_name;
            $category->description = $request->description;
            $category->parent_cat = $parentCatId;


            $this->changeStatus($category->status, $category);
            if ($request->file('image')) {
                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'Category-'.rand(0, 999999).'-'. date('YmdHis') . '.' . $ext;
                Log::info($aImage->getRealPath());
                $path = $this->imageController->Upload($this->songCategoryImagePath, $aImage, $fileName, $category->categoryId)."/".$fileName;
                $category->image =  $fileName;
            }else if($request->has('image_removed') && $request->get('image_removed') == 1){
                $category->image =  null;
            }

            $category->search_tag = $request->cat_tags;
            $category->save();
            return redirect(route('songs-category.index'))->with(['success' => true,
                'success.message' => "Successfully updated song category",
                'success.title' => 'Success']);
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage(), $request->all());
            return redirect(route('songs-category.index', $category->categoryId))->with([
                'error' => true,
                'error.message'=> 'Error updating song category. Please try again. Ex: '. $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function toggleStatus($songCat){
        try {
            $songCat = SongsCategory::with('childs')
                ->find($songCat);

            $this->changeStatus($songCat->status == 1 ? 0 : 1, $songCat);

        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return response()->json("error : ". $exceptionId, 403);
        }
    }

    function changeStatus($toStatus, $parentCat){
        $childCats = $parentCat->childs;
        if ($childCats && $childCats->count() > 0){
            foreach ($childCats as $childCat){
                $this->changeStatus($toStatus, $childCat);
            }
        }
        $parentCat->status = $toStatus;
        $parentCat->save();
    }

    public function imageDelete(Request $request)
    {
        if ($request->has('key')) {
            $id = $request->get('key');
            $songComposer = SongsCategory::find($id);
            $songComposer->update([
                'image' => ''
            ]);

            // activity
            /*if (Sentinel::getUser()) {
                parent::activity_create('Deleted product image.');
            }*/
            return 2;
        }
        return 1;
    }
}
