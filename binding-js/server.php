<?php
if( isset($_SERVER['PATH_INFO']) && substr( $_SERVER['PATH_INFO'], 0, 4 ) === "/v1/") {
    $baseUrl = 'http://localhost:5000';
    $url = $baseUrl.$_SERVER['PATH_INFO'];

    $ch = curl_init();

    $isPostRequest = $_SERVER['REQUEST_METHOD'] == 'POST';

    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, $isPostRequest);

    if($isPostRequest) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($_REQUEST));
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        )
    );

    $server_output = curl_exec($ch);

    curl_close ($ch);

    echo $server_output;

    die();
}