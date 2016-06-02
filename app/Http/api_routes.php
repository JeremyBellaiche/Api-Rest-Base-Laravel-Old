<?php
	
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

	$api->post('auth/login', 'App\Api\V1\Controllers\AuthController@login');
	$api->post('auth/signup', 'App\Api\V1\Controllers\AuthController@signup');
	$api->post('auth/recovery', 'App\Api\V1\Controllers\AuthController@recovery');
	$api->post('auth/reset', 'App\Api\V1\Controllers\AuthController@reset');

	// example of protected route
	$api->get('protected', ['middleware' => ['api.auth'], function () {		
		return \App\User::all();
    }]);

	// example of free route
	$api->get('free', function() {
		return \App\User::all();
	});

	// Chat
	$api->group(['prefix' => 'chats'], function($api){
		$api->get('/', 'App\Api\V1\Controllers\ChatController@index');
		$api->post('/create', 'App\Api\V1\Controllers\ChatController@create');
		$api->get('/{id}', 'App\Api\V1\Controllers\ChatController@show');
		$api->post('/{id}/send', 'App\Api\V1\Controllers\ChatController@sendMessage');
	});

	$api->group(['prefix' => 'users'], function($api){
		$api->get('/', '\App\Api\V1\Controllers\UserController@index');
		$api->post('/search', '\App\Api\V1\Controllers\UserController@search');
	});

	$api->group(['prefix' => 'contacts'], function($api){
		$api->get('/', '\App\Api\V1\Controllers\ContactController@index');
		$api->delete('/{id}', '\App\Api\V1\Controllers\ContactController@destroy');
	});

	$api->group(['prefix' => 'requests'], function($api){
		$api->get('/', '\App\Api\V1\Controllers\ContactController@indexRequests');
		$api->get('/{id}/{status}', '\App\Api\V1\Controllers\ContactController@setInvitation');
		$api->post('/', '\App\Api\V1\Controllers\ContactController@createRequest');
	});

});
