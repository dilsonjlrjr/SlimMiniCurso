<?php

require_once '../vendor/autoload.php';

$app = new \Slim\Slim();

$app->get('/soma/:num1/:num2', function ($num1,$num2) use($app) {
	echo $num1 + $num2;
});

$app->get('/subtracao/:num1/:num2', function ($num1,$num2) use($app) {
	echo $num1 - $num2;
});

$app->get('/multiplicacao/:num1/:num2', function ($num1,$num2) use($app) {
	echo $num1 * $num2;
});

$app->get('/divisao/:num1/:num2', function ($num1,$num2) use($app) {
	try {
		echo $num1/$num2;
	} catch (\Exception $e) {
		echo "Número não pode ser divisível por zero";
	}
});

$app->run();