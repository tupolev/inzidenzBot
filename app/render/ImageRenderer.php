<?php


namespace App\render;


use Imagick;
use ImagickPixel;

class ImageRenderer
{
    private string $imageDirectory;

    public function __construct(string $imageDirectory)
    {
        $this->imageDirectory = $imageDirectory;
    }

    final public function generatePngFromPdfBuffer(string $pdfBuffer, string $filetitle): string
    {
        $im = new imagick();
        $im->setResolution(350,350);
        $im->readImageBlob($pdfBuffer);
        $im->setBackgroundColor(new ImagickPixel("white"));
        $im->setImageBackgroundColor(new ImagickPixel("white"));
        $im->setCompressionQuality(90);
        $im->setCompression(0);
        $im->setAntiAlias(true);
        $im->resetIterator();
        $jointIm = $im->appendImages(true);
        $filename = $this->imageDirectory . '/' . $filetitle.'.jpg';
        $jointIm->writeImage($filename);
        $jointIm->clear();
        $jointIm->destroy();
        $im->clear();
        $im->destroy();

        return $filename;
    }
}