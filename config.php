<?php
/**
 * main config
 */
$config = [
	"debug" => true,
	"data_dir" => dirname(__FILE__) . "/data/",
	"data_files" => [
		"profile" => "profile.json",
		"skills" => "skills.json",
	],
	"smtp_config" => [
		"host" => "xxx",
		"username" => "xxx",
		"password" => "xxx",
		"port" => 587,
		"secure" => "TLS",
		"smtp_auth" => true,
		"receiver" => ["mail" => "xxx", "name" => "xxx"],
	],
];

/**
 * config for JSON-Middleware
 */
$json_config = [
	"json.status" => true,
	"json.override_error" => true,
	"json.override_notfound" => true,
];

/**
 * include customized config file
 */
if (file_exists(dirname(__FILE__) . "/my_config.php")) {
	include dirname(__FILE__) . "/my_config.php";
}