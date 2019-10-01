<?php

require_once(__DIR__."/appinfo.php");

if(isset($_POST["refresh_token"])) {
	$refresh_token = openssl_decrypt(base64_decode($_POST["refresh_token"]), ENCRYPTION_METHOD, ENCRYPTION_PASSWORD);
	
	$query = [
		"grant_type" => "refresh_token",
		"refresh_token" => $refresh_token
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
	
	http_response_code($http_code);
	header('Content-Type: application/json');
	echo $response;
} else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
                "error" => "invalid_request",
                "error_description" => "missing field for refresh_token"
        ]);
}

