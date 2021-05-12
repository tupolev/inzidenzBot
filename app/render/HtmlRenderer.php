<?php


namespace App\render;

class HtmlRenderer
{
    final public static function fromStateResultsData(array $stateData): string
    {
        $html  = '<html lang="de"><head><title></title>';
        $html .= '<style type="text/css">';
        $html .= 'td {border: 0px solid black; padding: 5px; font-size: 13px;}';
        $html .= '</style></head>';
        $html .= '<body style="background-color: white"><p>';
        $html .= '<table style="border-collapse: collapse; border: 0px solid black; width: 400px; color: black; font-family: arial;">';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td style="width: 100%;font-weight:bold;padding-bottom: 20px;border-bottom: 1px solid black;" colspan="3">Covid19 7-Tage-Inzidenz</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="width: 100%;font-weight:bold;" colspan="3">Land: ' . $stateData["label"] . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="width: 33.3333%;font-weight:bold;">Kreis / Bezirk</td>';
        $html .= '<td style="width: 33.3333%;font-weight:bold;">Wert</td>';
        $html .= '<td style="width: 33.3333%;font-weight:bold;">Datenstand</td>';
        $html .= '</tr>';

        foreach ($stateData["data"] as $province) {
            $html .= '<tr>';
            $html .= '<td style="width: 33.3333%;">' . $province["label"] . '</td>';
            $html .= '<td style="width: 33.3333%;">' . ceil($province["data"]->getInzidenzValue()) . '</td>';
            $html .= '<td style="width: 33.3333%;">' . substr($province["data"]->getLastUpdated(), 0, 5) . '</td>';
            $html .= '</tr>';
        }

        $html .= '<tr>';
        $html .= '<td colspan="3" style="width: 100%; font-style: italic; padding-top: 50px;border-top: 1px solid black;">Quelle: RKI/Arcgis.</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="3" style="width: 100%; font-weight: bold; padding-top: 10px;">twitter.com/@inzidenzcovid</td>';
        $html .= '</tr>';

        $html .= '</tbody></table></p></body></html>';

        return $html;
    }
}