<?php


namespace App\render;


use Spipu\Html2Pdf\Html2Pdf;

class PdfRenderer
{
    final public function generatePdfBufferFromHtml(string $html): string
    {
        $html2pdf = new HTML2PDF('P', 'A5', 'de');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($html);

        return $html2pdf->Output('temp.pdf','S');
    }
}