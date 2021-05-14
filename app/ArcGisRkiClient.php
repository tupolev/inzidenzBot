<?php


namespace App;


use App\Config\DAO\ArcGisRkiResultDataRow;
use Flow\JSONPath\JSONPath;
use GuzzleHttp\Client;

class ArcGisRkiClient
{
    const QUERY = "https://services7.arcgis.com/mOBPykOjAyBO2ZKk/arcgis/rest/services/RKI_Landkreisdaten/FeatureServer/0/query?" .
    "where=BL = '%%STATE_INTERNAL%%' AND BEZ = '%%REFTYPE%%' AND GEN = '%%PROVINCE_INTERNAL%%'&outFields=cases7_per_100k,last_update,GEN,cases7_bl_per_100k&returnGeometry=false&returnDistinctValues=true&f=json";

    const REFTYPE_BEZIRK = "Bezirk";

    private Client $httpClient;

    /**
     * ArcGisRkiClient constructor.
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public static function buildFullQuery(string $stateInternal, string $provinceInternal, string $reftype): string
    {
        return str_replace(
            ["%%STATE_INTERNAL%%", "%%PROVINCE_INTERNAL%%", "%%REFTYPE%%"],
            [$stateInternal, $provinceInternal, $reftype],
            self::QUERY
        );
    }

    final public function getParsedDataFromQuery(string $stateInternal, string $provinceInternal, string $reftype): ArcGisRkiResultDataRow
    {
        $resultData = $this->runQuery($stateInternal, $provinceInternal, $reftype);
        $jp = new JSONPath($resultData);
        $inzidenzValue = $jp->find('$.features[0].attributes.cases7_per_100k')->first();
        $lastUpdated = $jp->find('$.features[0].attributes.last_update')->first();

        return new ArcGisRkiResultDataRow($stateInternal, $provinceInternal, $inzidenzValue, $lastUpdated, $reftype);
    }

    //Special case for Berlin only
    //The aggregated value for the city is retrieved from the cases7_bl_per_100k (BL for Bundesland) in the bezirk children
    final public function getParsedDataFromBezirkQuery(string $stateInternal, string $provinceInternal): ArcGisRkiResultDataRow
    {
        $resultData = $this->runQuery($stateInternal, $provinceInternal, self::REFTYPE_BEZIRK);
        $jp = new JSONPath($resultData);
        $inzidenzValue = $jp->find('$.features[0].attributes.cases7_bl_per_100k')->first();
        $lastUpdated = $jp->find('$.features[0].attributes.last_update')->first();

        return new ArcGisRkiResultDataRow($stateInternal, $provinceInternal, $inzidenzValue, $lastUpdated, self::REFTYPE_BEZIRK);
    }

    private function runQuery(string $stateInternal, string $provinceInternal, string $reftype): array
    {
        $queryFullUrl = self::buildFullQuery($stateInternal, $provinceInternal, $reftype);

        $responseAsString = $this->httpClient->get($queryFullUrl)->getBody()->getContents();

        return json_decode($responseAsString, true);
    }
}