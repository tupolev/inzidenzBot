<?php

namespace App;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Config\DAO\ArcGisRkiResultDataRow;
use App\Config\DAO\Config;

class App {
    private Config $config;
    private ArcGisRkiClient $arcGisRkiClient;
    private TwitterOAuth $twitter;
    private ImageCreator $imageCreator;

    public function __construct(
        Config $config,
        ArcGisRkiClient $arcGisRkiClient,
        TwitterOAuth $twitter,
        ImageCreator $imageCreator
    )
    {
        $this->config = $config;
        $this->arcGisRkiClient = $arcGisRkiClient;
        $this->twitter = $twitter;
        $this->imageCreator = $imageCreator;
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

                $this->tweetImage(
                    $this->imageCreator->renderImageFromStateData($countryResults[$stateInternal])
                );
            }

        }
    }

    private function tweetImage(string $imageFullPath, string $status = ""): array
    {
        $media1 = $this->twitter->upload('media/upload', ['media' => $imageFullPath]);
        $parameters = [
            'status' => $status,
            'media_ids' => implode(',', [$media1->media_id_string])
        ];

        return $this->twitter->post('statuses/update', $parameters);
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
