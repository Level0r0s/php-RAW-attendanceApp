<?php

$url = 'http://bidyapith2.nihalit.com/index.php?admin/getStdInfo';


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$result = json_decode($response, true);


print_r($result);
