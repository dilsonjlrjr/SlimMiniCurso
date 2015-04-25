<?php

require_once '../../vendor/autoload.php';

$app = new \Slim\Slim();

$app->get('/soma/', function () use($app) {
	$numero1 = $app->request->get('numero1');
	$numero2 = $app->request->get('numero2');

	$resultado = $numero1 + $numero2;

	$app->response->headers->set('Content-Type', 'plain/text');
	$app->response->setBody($resultado);
});

$app->get('/subtracao/', function () use($app) {
	$numero1 = $app->request->get('numero1');
	$numero2 = $app->request->get('numero2');

	$resultado = $numero1 - $numero2;

	$app->response->headers->set('Content-Type', 'plain/text');
	$app->response->setBody($resultado);
});

$app->get('/multiplicacao/', function () use($app) {
	$numero1 = $app->request->get('numero1');
	$numero2 = $app->request->get('numero2');

	$resultado = $numero1 * $numero2;

	$app->response->headers->set('Content-Type', 'plain/text');
	$app->response->setBody($resultado);
});

$app->get('/divisao/', function () use($app) {
	$numero1 = $app->request->get('numero1');
	$numero2 = $app->request->get('numero2');

	try {
		$resultado = $numero1 / $numero2;
	} catch (\Exception $e) {
		$resultado = "Número não pode ser divisível por zero";
	}

	$app->response->headers->set('Content-Type', 'plain/text');
	$app->response->setBody($resultado);
});

$app->run();