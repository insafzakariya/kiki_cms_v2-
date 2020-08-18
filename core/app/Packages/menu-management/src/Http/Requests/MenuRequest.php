<?php
namespace MenuManage\Http\Requests;

use App\Http\Requests\Request;

class MenuRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$rules = [
			'label' => 'required',
			'menu_url' => 'required',
			'parent_menu' => 'required'
			];
		return $rules;
	}

}
