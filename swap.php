<?php

require_once(__DIR__."/appinfo.php");

if(isset($_POST["code"])) {
	$auth_code = $_POST["code"];
	
	$query = [
		"grant_type" => "authorization_code",
		"redirect_uri" => CLIENT_CALLBACK_URL,
		"code" => $auth_code
	];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Content-Type: application/x-www-form-urlencoded",
		"Authorization: $AUTH_HEADER"
	]);
	curl_setopt($ch, CURLOPT_URL, SPOTIFY_URL);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	error_log("got response: ".$response);
	if(!empty($response)) {
		$response_json = json_decode($response, true);
		if(!empty($response_json['refresh_token'])) {
			$response_json['refresh_token'] = base64_encode(openssl_encrypt($response_json['refresh_token'], ENCRYPTION_METHOD, ENCRYPTION_PASSWORD));
		}
		http_response_code($http_code);
		header('Content-Type: application/json');
		echo json_encode($response_json);
	} else {
		http_response_code(500);
		header('Content-Type: application/json');
		echo json_encode([
			"error" => "unknown",
			"error_description" => "An empty response was recieved"
		]);
        }
} else {
	http_response_code(400);
	header('Content-Type: application/json');
	echo json_encode([
		"error" => "invalid_request",
		"error_description" => "missing field for code"
	]);
}

