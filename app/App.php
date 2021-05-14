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
                    foreach ($state["provinces"] as $province)
                    {
                        $provinceInternal = $province["internal"];
                        $provinceLabel = $province["label"];
                        $reftype = "Kreisfreie Stadt";

                        $stateResults[$provinceInternal]= [
                            "internal" => "BERLIN",
                            "label" => "Berlin",
                            "reftype" => $reftype,
                            //"Berlin", "Berlin Mitte", "bezirk"
                            "data" => $arcGisRkiClient->getParsedDataFromBezirkQuery($stateInternal, $provinceInternal),
                        ];
                        break;
                    }
                } else {
                    foreach ($state["provinces"] as $province)
                    {
                        $provinceInternal = $province["internal"];
                        $provinceLabel = $province["label"];
                        $reftype = $province["reftype"];
                        printf(json_encode([$provinceInternal, $provinceLabel, $reftype]) . "\n");
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

                $imagePath = $this->imageCreator->renderImageFromStateData($countryResults[$stateInternal]);
                if (getenv('DEBUG') !== "true") {
                    $this->tweetImage($imagePath);
                }
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
}
