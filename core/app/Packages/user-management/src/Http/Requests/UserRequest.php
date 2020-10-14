<?php
namespace UserManage\Http\Requests;

use App\Http\Requests\Request;
use Input;

class UserRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){    

		$rules = [
			// 'first_name' => 'required',			
			'last_name' => 'required',			
		];	
		
		return $rules;
	}

}
