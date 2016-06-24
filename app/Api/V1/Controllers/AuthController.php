<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use Validator;
use Config;
use App\User;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\ValidationHttpException;

class AuthController extends Controller
{
    use Helpers;

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $validator = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized();
            }
            if( ! $user = \Auth::attempt($credentials)){
              return $this->response->errorUnauthorized();
            }
        } catch (JWTException $e) {
            return $this->response->error('could_not_create_token', 500);
        }

        return response()->json([
          'id' => \Auth::user()->id,
          'fname' =>  \Auth::user()->fname,
          'lname' =>  \Auth::user()->lname,
          'avatar'  =>  \Auth::user()->avatar,
          'wallet' => [
              'balance' => 20.00,
              'currency' => '€'
          ],
          'plan' => [
            [
              'icon'  => 'http://google.com',
              'max'   => 100,
              'current' => 80,
              'unit'  =>  'sms',
              'name'  =>  'Pack SMS'
            ],
            [
              'icon'  => 'http://google.com',
              'max'   => 200,
              'current' => 150,
              'unit'  =>  'data',
              'name'  =>  'Pack DATA'
            ],
            [
              'icon'  => 'http://google.com',
              'max'   => 300,
              'current' => 290,
              'unit'  =>  'minutes',
              'name'  =>  'Pack Minutes'
            ]
          ],
          'devices' => \Auth::user()->getDevices,
          'token' => $token,
        ]);
    }

    public function signup(Request $request)
    {



        $signupFields = Config::get('boilerplate.signup_fields');
        $hasToReleaseToken = Config::get('boilerplate.signup_token_release');

        $userData = $request->only($signupFields);

        $validator = Validator::make($userData, Config::get('boilerplate.signup_fields_rules'));

        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        User::unguard();
        $user = User::create($userData);
        User::reguard();

        if(!$user->id) {
            return $this->response->error('could_not_create_user', 500);
        }

        if($hasToReleaseToken) {
            return $this->login($request);
        }

        return $this->response->created();
    }

    public function recovery(Request $request)
    {
        $validator = Validator::make($request->only('email'), [
            'email' => 'required'
        ]);

        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject(Config::get('boilerplate.recovery_email_subject'));
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->response->noContent();
            case Password::INVALID_USER:
                return $this->response->errorNotFound();
        }
    }

    public function reset(Request $request)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $validator = Validator::make($credentials, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        if($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                if(Config::get('boilerplate.reset_token_release')) {
                    return $this->login($request);
                }
                return $this->response->noContent();

            default:
                return $this->response->error('could_not_reset_password', 500);
        }
    }


    public function loginWithPhone(Request $request){
      $credentials = $request->only(['phone_number', 'country_code', 'password']);

      $validator = Validator::make($credentials, [
          'phone_number' => 'required',
          'country_code' => 'required',
          'password' => 'required',
      ]);

      if($validator->fails()) {
          throw new ValidationHttpException($validator->errors()->all());
      }

      // Get user data from phone
      $device = Device::where([
        'phone_number'  =>  $request->get('phone_number'),
        'country_code'  =>  $request->get('country_code'),
      ])->with(['getUser'])->firstOrFail();

      $request['email'] = $device->getUser->email;

      return $this->login($request);
    }

    public function signupWithPhone(Request $request){
      // Step 1 : Validator
      $credentials = $request->only(['phone_number', 'country_code', 'password']);

      $validator = Validator::make($credentials, [
          'country_code' => 'required',
          'phone_number' => 'required',
          'password' => 'required',
      ]);

      $checkIfExist = Device::where([
        'phone_number'  => $request->get('phone_number'),
        'country_code'  => $request->get('country_code')
      ])->first();

      if($checkIfExist){
        return json_encode([
            'message' => 'The phone already exist, please try to login or recover your password'
        ]);
      }
      
      // Step 2 : create fake email
      $fakeEmail = $request->get('country_code').'-'.$request->get('phone_number').'@engagementplatform.com';
      $request['email'] = $fakeEmail;

      // Step 3 : Create account
      $user = User::create([
        'email' => $fakeEmail,
        'password' => $request->get('password'),
      ]);

      // Step 4 : Create Device
      $device = Device::create([
        'fk_user_id'    =>  $user->id,
        'country_code'  =>  $request->get('country_code'),
        'phone_number'  =>  $request->get('phone_number')
      ]);

      return $this->login($request);
    }
}
