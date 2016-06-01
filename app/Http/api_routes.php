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
	$api->group([], function($api){
		$api->get('chats', 'App\Api\V1\Controllers\ChatController@index');
		$api->put('chats/create', 'App\Api\V1\Controllers\ChatController@create');
		$api->get('chats/{id}', 'App\Api\V1\Controllers\ChatController@show');
	});

	$api->group([], function($api){
		$api->get('users', '\App\Api\V1\Controllers\UserController@index');
		$api->post('users/search', '\App\Api\V1\Controllers\UserController@search');
	});

});
