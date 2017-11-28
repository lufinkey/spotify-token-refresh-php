<?php

require_once(__DIR__."/appinfo.php");

if(isset($_POST["code"]))
{
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
	curl_close($ch);
	
	if(!empty($response))
	{
		header('Content-Type: application/json');
		$response_json = json_decode($response);
		$response_json->refresh_token = base64_encode(openssl_encrypt($response_json->refresh_token, ENCRYPTION_METHOD, ENCRYPTION_PASSWORD));
		echo json_encode($response_json);
	}
}

