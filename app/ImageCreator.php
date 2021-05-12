<?php


namespace App;


use App\render\HtmlRenderer;
use App\render\ImageRenderer;
use App\render\PdfRenderer;

/**
 * Class ImageCreator
 * The basic process is:
 *      rendering html from the input state results list,
 *      converting html to pdf
 *      converting pdf to png
 *      returning png path
 * @package App
 */
class ImageCreator
{

    public function __construct(
        HtmlRenderer $htmlRenderer,
        PdfRenderer $pdfRenderer,
        ImageRenderer $imageRenderer
    )
    {
        $this->htmlRenderer = $htmlRenderer;
        $this->pdfRenderer = $pdfRenderer;
        $this->imageRenderer = $imageRenderer;
    }

    final public function renderImageFromStateData(array $stateResultsData): string
    {
        $html = $this->htmlRenderer->fromStateResultsData($stateResultsData);
        $pdfPath = $this->pdfRenderer->generatePdfFromHtml($html);

        return  $this->imageRenderer->generateFromPdf($pdfPath);
    }
}