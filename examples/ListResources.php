<?php

include './vendor/autoload.php';

$url = 'https://storage.apspborgo.lan/';
$user = 'amministrazione';
$password = 'amministrazione';

$client = new \muka\OwnCloud\Client($url, $user, $password);

$list = $client->getResources();

var_dump($list);