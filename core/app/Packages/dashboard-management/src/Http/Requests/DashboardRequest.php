<?php
namespace DashboardManage\Http\Requests;

use App\Http\Requests\Request;
use Input;

class DashboardRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){    

	
		$rules = [
			
		];
	
		
		return $rules;
	}

	public function messages(){

		$messages = [
			
		];

		return $messages;
	}

}
