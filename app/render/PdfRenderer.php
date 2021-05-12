<?php


namespace App\render;


use Spipu\Html2Pdf\Html2Pdf;

class PdfRenderer
{

    final public function generatePdfFromHtml(string $html): string
    {
        //TODO make it work

        $html2pdf = new HTML2PDF('P', 'A4');
        $html2pdf->writeHTML($html);

        return $html2pdf->Output('temp.pdf','F');
    }
}