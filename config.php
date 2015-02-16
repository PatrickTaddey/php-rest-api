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
	"mail_config" => [
		"receiver" => ["mail" => "p.taddey@oldenbuerger.org", "name" => "Patrick Taddey"],
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