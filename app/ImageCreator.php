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
 *      converting pdf to jpeg
 *      returning jpeg path
 * @package App
 */
class ImageCreator
{
    const USE_MULTI_IMAGE = true;
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
        return  $this->imageRenderer->generatePngFromPdfBuffer(
            $this->pdfRenderer->generatePdfBufferFromHtml(
                $this->htmlRenderer::fromStateResultsData($stateResultsData)
            ),
            $stateResultsData["internal"],
            self::USE_MULTI_IMAGE
        );
    }
}