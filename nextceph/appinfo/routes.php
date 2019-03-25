<?php

return [
    'routes' => [
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
	   ['name' => 'page#osd', 'url' => '/osd', 'verb' => 'GET'],
     ['name' => 'page#mon', 'url' => '/mon', 'verb' => 'GET'],
     ['name' => 'page#pool', 'url' => '/pool', 'verb' => 'GET'],
     ['name' => 'page#host', 'url' => '/host', 'verb' => 'GET'],
     ['name' => 'page#config', 'url' => '/config', 'verb' => 'GET'],
     ['name' => 'page#log', 'url' => '/log', 'verb' => 'GET'],
     ['name' => 'page#perform', 'url' => '/perform', 'verb' => 'GET'],
     ['name' => 'page#crush', 'url' => '/crush', 'verb' => 'GET'],
	   ['name' => 'page#apply', 'url' => '/apply', 'verb' => 'POST'],
     ['name' => 'page#returnJSON', 'url' => '/json', 'verb' => 'GET'],
    ]
];
