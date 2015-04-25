<?php
	require '../vendor/autoload.php';

	$app = new \Slim\Slim();

	$app->get('/hello/:name', function ($name) {
    	echo "Hello, $name";
	});

	$app->get('/hello', function () use($app) {
		$name = $app->request->get("name");
    	echo "Hello, $name";
	});

	$app->run();