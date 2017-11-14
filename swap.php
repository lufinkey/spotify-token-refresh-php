<?php

require_once(__DIR__."/appinfo.php");

const SPOTIFY_URL = "https://accounts.spotify.com";

$auth_header = "Basic ".base64_encode(CLIENT_ID.":".CLIENT_SECRET);

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
		"Authorization: $auth_header"
	]);
	curl_setopt($ch, CURLOPT_URL, SPOTIFY_URL."/api/token");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
	$response = curl_exec($ch);
	curl_close($ch);
	
	echo $response;
}
