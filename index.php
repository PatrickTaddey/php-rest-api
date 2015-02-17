<?php
/**
 * Simple Rest-API example with SlimPHP - http://www.slimframework.com/
 *
 * This API just uses JSON-Files for sharing data with the frontend
 */
require "config.php";
require "vendor/autoload.php";

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
$response->header("Access-Control-Allow-Origin", "*");
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
	if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]) && (
		$_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"] == "POST" ||
		$_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"] == "DELETE" ||
		$_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"] == "PUT")) {
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Headers: X-Requested-With");
		header("Access-Control-Allow-Headers: Content-Type");
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); // http://stackoverflow.com/a/7605119/578667
		header("Access-Control-Max-Age: 86400");
	}
	exit;
}

/**
 * handle GET-Requests to share user data from JSON-Files
 */
$app->get("/users/:name(/:data)", function ($name, $data_type = "profile") use ($app) {

	if (isset($app->config("data_files")[$data_type])) {
		$data_file = $app->config("data_dir") . $app->config("data_files")[$data_type];
		if (file_exists($data_file) === true) {
			$data = ["data" => json_decode(file_get_contents($data_file))];
			$app->render(200, $data);
		} else {
			$app->render(404, ["message" => "Not Found"]);
		}
	} else {
		$app->render(404, ["message" => "Not Found"]);
	}
});

/**
 * handle POST-Requests: send mail
 */
$app->post("/contacts", function () use ($app) {
	$request_body = json_decode($app->request->getBody());
	$mail_config = $app->config("mail_config");

	$mail = new PHPMailer;
	$mail->From = $mail_config["receiver"]['mail'];
	$mail->FromName = $request_body->name;
	$mail->addAddress($mail_config["receiver"]['mail'], $mail_config["receiver"]['name']);
	$mail->addReplyTo($request_body->email, $request_body->name);
	$mail->Subject = "Nachricht von " . $request_body->name;
	$mail->Body = $request_body->message;
	if (empty($request_body->name) === false) {
		$mail->Body .= "\n\n Angebot: " . $request_body->offer;
	}

	if (!$mail->send()) {
		$app->render(500, ["message" => $mail->ErrorInfo]);
	} else {
		$app->render(200, ["message" => "Message sent"]);
	}

});

/**
 * run app, that"s all!
 */
$app->run();