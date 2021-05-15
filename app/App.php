<?php

namespace App;

use Abraham\TwitterOAuth\TwitterOAuth;
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

                if ($stateInternal === "BERLIN") {
                    $province = $state["provinces"][0];
                    $provinceInternal = $province["internal"];
                    $reftype = "Kreisfreie Stadt";

                    $stateResults[$provinceInternal]= [
                        "internal" => "BERLIN",
                        "label" => "Berlin",
                        "reftype" => $reftype,
                        //"Berlin", "Berlin Mitte", "bezirk"
                        "data" => $arcGisRkiClient->getParsedDataFromBezirkQuery($stateInternal, $provinceInternal),
                    ];
                } else {
                    foreach ($state["provinces"] as $province)
                    {
                        $provinceInternal = $province["internal"];
                        $provinceLabel = $province["label"];
                        $reftype = $province["reftype"];
                        self::log($provinceLabel, $reftype);
                        $stateResults[$provinceInternal]= [
                            "internal" => $provinceInternal,
                            "label" => $provinceLabel,
                            "reftype" => $reftype,
                            "data" => $arcGisRkiClient->getParsedDataFromQuery($stateInternal, $provinceInternal, $reftype),
                        ];
                    }
                }

                $countryResults[$stateInternal]= [
                    "internal" => $stateInternal,
                    "label" => $stateLabel,
                    "data" => $stateResults
                ];

                $imagesPath = $this->imageCreator->renderImageFromStateData($countryResults[$stateInternal]);
                $this->tweetImages($imagesPath, 'Covid19 7-Tage-Inzidenz in ' . $stateLabel . ' (' . date("j\.n\.Y") . ')');
            }

        }
        self::log("Process", "finished");
    }

    private function tweetImages(array $imageFullPaths, string $status = ""): void
    {
        $medias = [];
        foreach ($imageFullPaths as $index => $imageFullPath) {
            $media = $this->twitter->upload('media/upload', ['media' => $imageFullPath]);
            $medias[]= $media->media_id_string;
        }
        $parameters = [
            'status' => $status,
            'media_ids' => implode(',', $medias)
        ];

        $this->twitter->post('statuses/update', $parameters);
    }

    private static function log(string $provinceLabel, string $reftype): void
    {
        printf("%s: %s, %s\n", date("j-n-Y H:i:s"), $provinceLabel, $reftype);
    }
}
