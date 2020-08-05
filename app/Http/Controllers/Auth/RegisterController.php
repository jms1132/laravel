<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = RouteServiceProvider::HOME;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
		//request의 validate 메소드를 사용하는 대신 validator 파사드를 사용해서 validator 인스턴스를 수동으로 생성했다.
		//*파사드란, 디자인패턴 중 하나로 복잡한 아키텍쳐를 숨기고 간략한 api로 접근하기 위해 추상화 하는 기법이다.
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'nickname' => ['required', 'string'],
			
			'password' => ['required', 'string', 'min:6', 'confirmed'],
			'type' => ['required', 'string'],
		]);
	}
	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{
		return User::create([
			'email' => $data['email'],
			'nickname' => $data['nickname'],
			'password' => Hash::make($data['password']),
			'type' => $data['type'],
		]);
	}
}
