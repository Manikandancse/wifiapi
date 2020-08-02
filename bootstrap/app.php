<?php

	session_start();
	
	
	require __DIR__ . '/../vendor/autoload.php';
	
	$config['displayErrorDetails'] = true;
	$config['addContentLengthHeader'] = false;

	$config['db']['host']   = 'localhost';
	$config['db']['user']   = 'root';
	$config['db']['pass']   = '';
	$config['db']['dbname'] = 'rapidwifi';
	
	/*$app = new \Slim\App([
		'settings' => [
			'displayErrorDetails' => true,
		],
		'db' => [
			'driver' => 'mysql',
			'host' => 'localhost',
			'dbname' => 'rapidwifi',
			'user' => 'root',
			'pass' => '',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => '',
		]
	]); */
	
	$app = new \Slim\App([
		'settings' => $config
	]);

	
	$container = $app->getContainer();
	
	$container['db'] = function ($c) {
		$db = $c['settings']['db'];
		$pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
			$db['user'], $db['pass']);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $pdo;
	};
	
	require __DIR__ . '/../app/routes.php';
	

?>