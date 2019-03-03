<?php

return [
    'routes' => [
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
	   ['name' => 'page#osd', 'url' => '/osd', 'verb' => 'GET'],
     ['name' => 'page#mon', 'url' => '/mon', 'verb' => 'GET'],
     ['name' => 'page#pool', 'url' => '/pool', 'verb' => 'GET'],
     ['name' => 'page#host', 'url' => '/host', 'verb' => 'GET'],
     ['name' => 'page#config', 'url' => '/config', 'verb' => 'GET'],
	   ['name' => 'page#do_echo', 'url' => '/echo', 'verb' => 'POST'],
    ]
];
