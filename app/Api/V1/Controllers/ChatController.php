<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;

use App\Models\Chat;
use App\Models\Chat_User;

class ChatController extends Controller
{
    use Helpers;


    public function index()
	{
		$currentUser = JWTAuth::parseToken()->authenticate()
	        ->chats()
	        ->get()
	        ->toArray();

	    return $currentUser;

	}

	public function show($id)
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		
		$response = Chat::where('id', $id)->with('messages')->first();

		if(!$response)
	        throw new NotFoundHttpException; 

		return json_encode($response->messages);


	}


	public function create(Request $request)
	{

		//$currentUser = JWTAuth::parseToken()->authenticate();

        $currentUser = JWTAuth::parseToken()->authenticate();

        if(!$request->has('users')){
	        return $this->response->error('users_are_missing', 500);
        }

        dd($request->get('users'));

        if(!$request->has('message')){
	        return $this->response->error('message_is_missing', 500);
        }

	    $chat = Chat::create([
	    	'title'	=>	($request->has('title')? $request->get('title') : ''),
	    	'fk_owner_id' => $currentUser->id
	    ]);	


        foreach ($request->get('users') as $user) {
        	Chat_User::create([
        		'fk_chat_id' => $chat->id,
        		'fk_user_id' => $user->id
        	]);
        }

        if($request->has('message')){
        	
        	$message = Message::create([
        		'fk_chat_id'	=>	$chat->id,
        		'fk_user_id'	=>	$currentUser->id,
        		'msg_text'		=>	$request->message->text // $request->message->text
        	]);

        	if(isset($request->message->attachment)){
        		foreach ($request->message->get('attachment') as $attachment) {
        			Message_Attachment::create([
        				'url' => $attachment->url,
        				'fk_user_id' => $currentUser->id,
        				'fk_message_id' => $message->id
        			]);
        		}
        	}

        };

        $response = [
        	'chat_id'	=>	$chat->id
        ];

        return $response;

	}

}
