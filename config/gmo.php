<?php
return [

    /*
    |--------------------------------------------------------------------------
    | GMO payment Configuration
    |--------------------------------------------------------------------------
    */

	'host'	=> env('GMO_HOST'),
    'site' => [
		'id'		=> env('GMO_SITE_ID'),
		'password'	=> env('GMO_SITE_PASS'),
	],
    'shop' => [
		'id'		=> env('GMO_SHOP_ID'),
		'password'	=> env('GMO_SHOP_PASS'),
	],
];
