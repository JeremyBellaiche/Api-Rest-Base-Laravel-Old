<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;
use App\User;
use App\Models\Contact;

class ContactController extends Controller
{
    use Helpers;

    public function indexRequests(){
		
		$currentUser = JWTAuth::parseToken()->authenticate()
	        ->invitations()
	        ->get()
	        ->toArray();

        $invitations = [];

	    return response()->json($currentUser);
    }

    public function createRequest(Request $request){
    	$currentUser = JWTAuth::parseToken()->authenticate();

    	$req = Contact::create([
    		'fk_user_id_applicant' => $currentUser->id,
    		'fk_user_id_intended'  => $request->user_id,
    		'status'			   => 'waiting'
    	]);

    	return response()->json($req);
    }

    public function setRequest($id, $status){
    	$currentUser = JWTAuth::parseToken()->authenticate();

    	$req = Contact::findOrFail($id);
    	$req->status = $status;
    	$req->save();

    	return response()->json($req);
    }

    public function index(){
    	$currentUser = JWTAuth::parseToken()->authenticate()
	        ->contacts()
	        ->toArray();

	    return response()->json($currentUser);

    }

	public function destroy($id){
		$currentUser = JWTAuth::parseToken()->authenticate();

		$contact = Contact::findOrFail($id);
		$contact->delete();

        return response()->json([
            'message' => 'Contact has been deleted'
        ]);
	}

}
