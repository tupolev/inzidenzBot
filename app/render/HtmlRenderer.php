<?php


namespace App\render;


use App\Config\DAO\ArcGisRkiResultDataRow;

class HtmlRenderer
{

    final public function fromStateResultsData(array $stateResultsData): string
    {
        return self::renderTweet($stateResultsData);
    }

    private static function renderTweet(array $stateData): string
    {
        //TODO modify and use renderTweet to return html

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