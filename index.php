<?php
/**
 * Simple Rest-API example with SlimPHP - http://www.slimframework.com/
 *
 * This API just uses JSON-Files for sharing data with the frontend
 */
require 'config.php';
require 'vendor/autoload.php';

/**
 * Simple setup: create app, set config, add Middleware
 */
$app = new \Slim\Slim();
$app->config($config);
$app->add(new \SlimJson\Middleware($json_config));

/**
 * enable cors
 */
$response = $app->response();
$response->header('Access-Control-Allow-Origin', '*');

/**
 * handle GET-Requests to share user data from JSON-Files
 */
$app->get('/users/:name(/:data)', function ($name, $data_type = "profile") use ($app) {
	if (isset($app->config('data_files')[$data_type])) {
		$data_file = $app->config('data_dir') . $app->config('data_files')[$data_type];
		if (file_exists($data_file) === true) {
			$data = (array) json_decode(file_get_contents($data_file));
			$app->render(200, $data);
		} else {
			$app->render(404, ["message" => "Not Found"]);
		}
	} else {
		$app->render(404, ["message" => "Not Found"]);
	}
});

/**
 * run app, that's all!
 */
$app->run();