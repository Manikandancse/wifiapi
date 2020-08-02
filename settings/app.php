	<?php

	session_start();
	
	
	require __DIR__ . '/../vendor/autoload.php';
	
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Headers:X-Request-With');

	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
	header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
	
	$config["determineRouteBeforeAppMiddleware"] = true;
	$config['displayErrorDetails'] = true;
	$config['addContentLengthHeader'] = false;

	$config['db']['host']   = 'localhost';
	$config['db']['user']   = 'root';
	$config['db']['pass']   = '';
	$config['db']['dbname'] = 'rapidwifi';
	
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
	
	require __DIR__ . '/../routes/routes.php';
	require __DIR__ . '/../routes/login.php';
	require __DIR__ . '/../routes/area.php';
	require __DIR__ . '/../routes/payments.php';
	require __DIR__ . '/../routes/plans.php';
	require __DIR__ . '/../routes/gst.php';
?>