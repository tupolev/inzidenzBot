<?php


namespace App\Config;


use App\Config\DAO\Config;
use App\Config\DAO\General;
use App\Config\DAO\GeoStructure;
use App\Config\DAO\Twitter;

class ConfigLoader
{
    const CONFIG_FILE_PATH = __DIR__ . "/../../resources/config.json";
    const GEOSTRUCTURE_FILE_PATH = __DIR__ . "/../../resources/geostructure.json";
    const AS_ASSOC_ARRAY = true;

    public static function load(): Config
    {
        $commonConfig = json_decode(file_get_contents(self::CONFIG_FILE_PATH), self::AS_ASSOC_ARRAY);
        $commonConfig['geostructure']= json_decode(file_get_contents(self::GEOSTRUCTURE_FILE_PATH), self::AS_ASSOC_ARRAY);

        return new Config(
            self::loadGeneralConfig($commonConfig["general"]),
            self::loadGeoStructureConfig($commonConfig['geostructure']),
            self::loadTwitterConfig($commonConfig["twitter"]),
        );
    }


    public static function loadTwitterConfigAsArray(array $config): array
    {
        return self::loadTwitterConfig($config)->toArray();
    }

    private static function loadGeneralConfig(array $settings): General
    {
        $general = new General();
        $general->setSettings($settings);

        return $general;
    }

    private static function loadTwitterConfig(array $config): Twitter
    {
        $twitter = new Twitter();
        $twitter->setAccessTokenKey($config["access_token_key"]);
        $twitter->setAccessTokenSecret($config["access_token_secret"]);
        $twitter->setConsumerKey($config["consumer_key"]);
        $twitter->setConsumerSecret($config["consumer_secret"]);
        $twitter->setInputEncoding($config["input_encoding"]);

        return $twitter;
    }

    private static function loadGeoStructureConfig(array $structure): GeoStructure
    {
        $geoStructure = new GeoStructure();
        $geoStructure->setStructure($structure);

        return $geoStructure;
    }
}