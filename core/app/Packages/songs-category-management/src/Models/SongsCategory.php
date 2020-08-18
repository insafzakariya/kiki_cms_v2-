<?php

namespace SongsCategory\Models;

use Illuminate\Database\Eloquent\Model;

class SongsCategory extends Model
{
    /**
     * Get the index name for the model.
     *
     * @return string
    */

        protected $primaryKey = "categoryId";

        protected $casts = [
            'search_tag' => 'array'
        ];

    protected $fillable = [
        'name',
        'description',
        'parent_cat',
        'image',
        'search_tag',
        'status'
    ];

    public function childs()
    {
        return $this->hasMany('SongsCategory\Models\SongsCategory','parent_cat','categoryId');
    }

    public static function getCategoryDropDown($selectedId = -1)
    {
        $return = "";
        $mainCats = SELF::has('childs')->with("childs")->whereStatus(1)->where("parent_cat", 0)->orderBy("name", "asc")->get();

        foreach ($mainCats as $mainCat) {
            $return .= SELF::setChilds($selectedId, $mainCat, $level = 0);
        }
        return $return;
    }


    public static function setChilds($selectedId, $cat, $level)
    {
        $level++;
        if ($cat->childs->count() > 0) {
            $children = SELF::with("childs")->whereStatus(1)->where("parent_cat", $cat->categoryId)->orderBy("name", "asc")->get();
            $sucCats = "";
            foreach($children as $child) {
                $sucCats .= SELF::setChilds($selectedId, $child, $level);
            }
            /*$selected = "";
            if($selectedId == $cat->id){
                $selected = "selected";
            }*/
            //return "<option ".$selected." value='$cat->id'>".str_repeat("&nbsp;", 2*$level).$cat->name."</option>". $sucCats;
            return "<optgroup label='$cat->name'>'.$sucCats.'</optgroup>";
        }else{
            $selected = "";
            if($selected != -1 && $selectedId == $cat->categoryId){
                $selected = "selected";
            }
            return "<option ".$selected." value='$cat->categoryId'>".str_repeat("&nbsp;", 2*$level).$cat->name."</option>";
            //return "<optgroup label='$cat->name'></optgroup>";
        }

    }


}
