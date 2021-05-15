<?php


namespace App\render;

class HtmlRenderer
{
    final public static function fromStateResultsData(array $stateData): string
    {
        $html = '<style type="text/css">';
        $html .= 'td {border: 0px solid black; padding: 5px; font-size: 13px;}';
        $html .= '</style>';
        $html .= '<page pageset="old." header="page_header" footer="page" backtop="20mm" backbottom="20mm" backcolor="#ffffff">';
        $html .= '<page_header>';
        $html .= '<div><h3>Covid19 7-Tage-Inzidenz in ' . $stateData["label"] . '</h3></div>';
        $html .= '<table style="border-collapse: collapse; border: 1px solid black; width: 450px; color: black; font-family: arial;">';
        $html .= '<tr style="padding-bottom: 5px">';
        $html .= '<td style="width: 30%;font-weight:bold; text-align:left;">Typ</td>';
        $html .= '<td style="width: 40%;font-weight:bold; text-align:left;">Name</td>';
        $html .= '<td style="width: 10%;font-weight:bold; text-align:center;">Wert</td>';
        $html .= '<td style="width: 20%;font-weight:bold; text-align:center;">Datenstand</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= '</page_header>';
        $html .= '<page_footer>';
        $html .= '<div style="width: 100%; font-style: italic; padding-top: 50px;">Quelle: RKI/Arcgis.</div>';
        $html .= '<br/>';
        $html .= '<div style="width: 100%; font-weight: bold; padding-top: 10px;">Twitter/@inzidenzcovid</div>';
        $html .= '</page_footer>';
        $html .= '<p>';
        $html .= '<table style="border-collapse: collapse; border: none; width: 450px; color: black; font-family: arial;">';
        $html .= '<tbody>';

        foreach ($stateData["data"] as $province) {
            $html .= '<tr>';
            $html .= '<td style="width: 30%; text-align:left;">' . $province["reftype"] . '</td>';
            $html .= '<td style="width: 40%; text-align:left;">' . $province["label"] . '</td>';
            $html .= '<td style="color:' . self::colorByValue(ceil($province["data"]->getInzidenzValue())) .  '; width: 10%; text-align:center; font-weight:bold">' . ceil($province["data"]->getInzidenzValue()) . '</td>';
            $html .= '<td style="width: 20%; text-align:center;">' . substr($province["data"]->getLastUpdated(), 0, 5) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        $html .= '</p>';
        $html .= '</page>';

        return $html;
    }

    private static function colorByValue(int $value): string
    {
        return ($value >= 100) ? 'red' : 'green';
    }
}