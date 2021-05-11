<?php

require_once "vendor/autoload.php";
require_once 'app/App.php';

use App\App;
use App\ArcGisRkiClient;
use App\Config\ConfigLoader;

$config = ConfigLoader::load();
(new App(
    $config,
    new ArcGisRkiClient(new GuzzleHttp\Client()),
    new TwitterAPIExchange($config->getTwitter()->toArray())
))->run();