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
        $html .= '<body style="background-color: white"><nobreak><p>';
        $html .= '<table style="border-collapse: collapse; border: none; width: 450px; color: black; font-family: arial;">';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td style="width: 100%;font-weight:bold;border-bottom: 1px solid black;" colspan="4"><h2>Covid19 7-Tage-Inzidenz</h2></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="width: 100%;font-weight:bold;padding-top: 15px;padding-bottom: 15px" colspan="4"><h3>Land: ' . $stateData["label"] . '</h3></td>';
        $html .= '</tr>';
        $html .= '<tr style="padding-bottom: 5px">';
        $html .= '<td style="width: 30%;font-weight:bold; text-align:left;">Typ</td>';
        $html .= '<td style="width: 40%;font-weight:bold; text-align:left;">Name</td>';
        $html .= '<td style="width: 10%;font-weight:bold; text-align:center;">Wert</td>';
        $html .= '<td style="width: 20%;font-weight:bold; text-align:center;">Datenstand</td>';
        $html .= '</tr>';

        foreach ($stateData["data"] as $province) {
            $html .= '<tr>';
            $html .= '<td style="border-top: 1px solid black; width: 30%; text-align:left;">' . $province["reftype"] . '</td>';
            $html .= '<td style="border-top: 1px solid black; width: 40%; text-align:left;">' . $province["label"] . '</td>';
            $html .= '<td style="border-top: 1px solid black; width: 10%; text-align:center;">' . ceil($province["data"]->getInzidenzValue()) . '</td>';
            $html .= '<td style="border-top: 1px solid black; width: 20%; text-align:center;">' . substr($province["data"]->getLastUpdated(), 0, 5) . '</td>';
            $html .= '</tr>';
        }

        $html .= '<tr>';
        $html .= '<td colspan="4" style="width: 100%; font-style: italic; padding-top: 50px;border-top: 1px solid black;">Quelle: RKI/Arcgis.</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="4" style="width: 100%; font-weight: bold; padding-top: 10px;">twitter.com/inzidenzcovid</td>';
        $html .= '</tr>';

        $html .= '</tbody></table></p></nobreak></body></html>';

        return $html;
    }
}