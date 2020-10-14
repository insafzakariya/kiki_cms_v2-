<?php namespace App\Http\Requests;

class RegisterRequest extends Request {

	public function authorize() {
		return true;
	}

	public function rules() {
		return $rules = [
			// 'email' => 'required|email|unique:users,email',
			// 'user_name' => 'required|min:5|unique:users,username',
			// 'password' => 'required|confirmed|min:6'
		];
	}
}
