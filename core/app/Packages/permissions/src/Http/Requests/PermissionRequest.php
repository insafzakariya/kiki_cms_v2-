<?php
namespace Permissions\Http\Requests;

use App\Http\Requests\Request;

class PermissionRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$rules = [
			'label' => 'required'			
			];
		return $rules;
	}

}
