<?php

require_once(__DIR__."/appinfo.php");

if(isset($_POST["refresh_token"]))
{
	$refresh_token = openssl_decrypt(base64_decode($_POST["refresh_token"]), ENCRYPTION_METHOD, ENCRYPTION_PASSWORD);
	
	$query = [
		"grant_type" => "refresh_token",
		"refresh_token" => $refresh_token
	];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Content-Type" : "application/x-www-form-urlencoded",
		"Authorization: $AUTH_HEADER"
	]);
	curl_setopt($ch, CURLOPT_URL, SPOTIFY_URL."/api/token");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
	$response = curl_exec($ch);
	curl_close($ch);
	
	header('Content-Type: application/json');
	echo $response;
}

