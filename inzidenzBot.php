<?php

require_once "vendor/autoload.php";
require_once 'app/App.php';

use Abraham\TwitterOAuth\TwitterOAuth;
use App\App;
use App\ArcGisRkiClient;
use App\Config\ConfigLoader;
use App\ImageCreator;
use App\render\HtmlRenderer;
use App\render\ImageRenderer;
use App\render\PdfRenderer;

$config = ConfigLoader::load();
(new App(
    $config,
    new ArcGisRkiClient(new GuzzleHttp\Client()),
    new TwitterOAuth(
        $config->getTwitter()->getConsumerKey(),
        $config->getTwitter()->getConsumerSecret(),
        $config->getTwitter()->getAccessTokenKey(),
        $config->getTwitter()->getAccessTokenSecret()
    ),
    new ImageCreator(
        new HtmlRenderer(),
        new PdfRenderer(),
        new ImageRenderer(__DIR__ . '/out/images')
    )
))->run();