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
        //TODO complete geostructure file with all states and provinces
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
