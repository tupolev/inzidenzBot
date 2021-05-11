<?php

namespace App;

use App\Config\DAO\ArcGisRkiResultDataRow;
use App\Config\DAO\Config;
use TwitterAPIExchange;

class App {
    private Config $config;
    private ArcGisRkiClient $arcGisRkiClient;
    private TwitterAPIExchange $twitter;

    public function __construct(Config $config, ArcGisRkiClient $arcGisRkiClient, TwitterAPIExchange $twitter)
    {
        $this->config = $config;
        $this->arcGisRkiClient = $arcGisRkiClient;
        $this->twitter = $twitter;
    }

    final public function run(): void
    {
        $config = $this->config;
        $arcGisRkiClient = $this->arcGisRkiClient;

        foreach ($config->getGeoStructure()->getStructure()["countries"] as $country)
        {
            $countryName = $country["name"];
            $countryResults = [
                "internal" => $countryName,
                "label" => $countryName,
                "data" => [],
            ];
            foreach ($country["states"] as $state)
            {
                $stateInternal = $state["internal"];
                $stateLabel = $state["label"];

                $stateResults = [];
                foreach ($state["provinces"] as $province)
                {
                    $provinceInternal = $province["internal"];
                    $provinceLabel = $province["label"];
                    $reftype = $province["reftype"];

                    $stateResults[$provinceInternal]= [
                        "internal" => $provinceInternal,
                        "label" => $provinceLabel,
                        "data" => $arcGisRkiClient->getParsedDataFromQuery($stateInternal, $provinceInternal, $reftype),
                    ];
                }
                $countryResults[$stateInternal]= [
                    "internal" => $stateInternal,
                    "label" => $stateLabel,
                    "data" => $stateResults
                ];

                //first approach: a tweet per state with province list
                $this->tweet($countryResults[$stateInternal]);
            }

        }
    }

    private function tweet(array $stateInternal): void
    {
        $url = "https://api.twitter.com/1.1/statuses/update.json";
        $requestMethod = "POST";
        $postfields = ["status" => self::renderTweet($stateInternal)];
//        file_put_contents(__DIR__ . "/../tmp.twitt", self::renderTweet($stateInternal));
        $this->twitter->buildOauth($url, $requestMethod)->setPostfields($postfields)->performRequest();
    }

    private static function renderTweet(array $stateData): string
    {
        $tweet =  $stateData["label"] . "\n";
        $tweet .=  "Land\tWert\tLetzte\tAktualisierung\n";
        $tweet .= "====\t====\t======\t==============\n";
        foreach ($stateData["data"] as $province) {
            /** @var ArcGisRkiResultDataRow */
            $provinceData = $province["data"];
            $tweet .= $province["label"] . "\t"
                . ceil($provinceData->getInzidenzValue()). "\t"
                . substr($provinceData->getLastUpdated(), 0, 5) . "\n";
        }

        return $tweet;
    }
}
