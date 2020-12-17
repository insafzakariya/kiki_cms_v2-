<?php
namespace App\Classes;

/**
*
* Dynamic Menu Generation
*
* @author Insaf Zakariya <insaf.zak@gmail.com>
* @version 1.0.0
* @copyright Copyright (c) 2015, Insaf Zakariya
*
*/

use Permissions\Models\Permission;
use Sentinel;
use Log;

class DynamicMenu{
  
  /**
   * Generate Dynamic Menu Function
   *
   * @param  Integer $parent        Parent
   * @param  Array   $menu          Arranged menu array
   * @param  Integer $level         Menu Level
   * @param  Integer $currentUrlId  Id of Current Route Url
   * @param  Integer $userId        Logged in User Id
   * @return String                 Generated html string
   */
  static function generateMenu($parent, $menu, $level, $currentUrl,$user){
    
    // $user = Sentinel::findUserById($userId);
    $html = "";

    if(!empty($menu)){
      foreach ($menu as $key => $element) {
        //$permissions = Permission::whereIn('name',json_decode($element->permissions))->where('status','=',1)->lists('name');
        // if($user->hasAnyAccess($permissions) && $element->status == 1){ 
        if($user->hasAnyAccess(json_decode($element->permissions)) && $element->status == 1){ 
          if(count($element->children) == 0){ 
          
            if($currentUrl && $currentUrl->id == $element->id){ 
              $html .= "<li class=\"active\">";
            }else{ 
              $html .= "<li>";
            }
            
            $html .= "<a href=\"".url($element->link)."\">";   
             if ($level==0) {
                $html .= "<i class='".$element->icon."'></i><span class='nav-label' >".$element->label."</span>";
             } else {
               $html .= "<i class='".$element->icon."'></i>".$element->label;
             }
            
            $html .= "</a></li>";

          }else{
          
            if($currentUrl && $element->isAncestorOf($currentUrl)){
              $html .= "<li class=\"active\">";
            }else{
              $html .= "<li >";
            }

            $html .= "<a href=\"".url($element->link)."\" >";


            $html .= "<i class='".$element->icon."'></i><span class='nav-label' >".$element->label."</span>";

            $html .= "<span class='fa arrow'></span></a>";

            $html .= "<ul class=\"nav nav-second-level collapse\">";
            // $html .= DynamicMenu::generateMenu($element->id, $element->children,$element->getLevel(), $currentUrl, $user);
            $html .= DynamicMenu::generateMenu($element->id, $element->children,$element->depth, $currentUrl, $user);
           
            $html .= "</ul>";

            $html .= "</li>";
          }
        }
      }
    }  
    
    return $html;
  }
}
