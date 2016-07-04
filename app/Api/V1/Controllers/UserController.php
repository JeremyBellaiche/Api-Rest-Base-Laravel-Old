<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;

use App\User;

class UserController extends Controller
{
    use Helpers;

	public function index()
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		
        $response = User::all();

		if(!$response->count()){
            $error = [
                'message' => 'Users can\'t be found'
            ];

            return response()->json($error);
        }
	        
		return response()->json($response);

    }

    public function search(Request $request){

        if(!$request->has('query')){
            $error = [
                'message'   =>  'Query parameter is missing'
            ];
            return response()->json($error);
        }

        $response = User::where('fname', 'LIKE', '%'.$request->get('query').'%')
        ->orWhere('lname', 'LIKE', '%'.$request->get('query').'%')
        ->get();

        return response()->json($response);

    }

}
