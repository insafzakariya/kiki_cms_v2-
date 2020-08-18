<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => '',
		'secret' => '',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'App\User',
		'secret' => '',
	],

	'facebook' => [
	    'client_id' => env("FACEBOOK_CLIENT_ID",'1009827709158608'),
	    'client_secret' => env("FACEBOOK_CLIENT_SECRET",'b5cd04c88f3e2bff0a88ede44cdcb981'),
	    'redirect' => 'http://localhost/SDC-LIVE/auth/facebook/callback',
	],
	'google' => [
	    'client_id' => env("GOOGLE_CLIENT_ID",'442918127719-f3nachml8rhuph71tu7ptoggbrt4tdu8.apps.googleusercontent.com'),
	    'client_secret' => env("GOOGLE_CLIENT_SECRET",'xkwnSj8Y_4wKXyMxltvuJarF'),
	    'redirect' => 'http://localhost/SDC-LIVE/auth/google/callback',
	],
	'usps' => [
		'username' => "114SOFTA1630"
	]

];
