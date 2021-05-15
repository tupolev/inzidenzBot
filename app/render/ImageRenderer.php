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

    /**
     * @param string $pdfBuffer
     * @param string $filetitle
     * @param bool $multiImage
     * @return string[]
     * @throws \ImagickException
     */
    final public function generateImagesFromPdfBuffer(string $pdfBuffer, string $filetitle, bool $multiImage = false): array
    {
        $filenames = [];
        $filename = $this->imageDirectory . '/' . $filetitle.'.jpg';
        $im = new imagick();
        $im->setResolution(350,350);
        $im->readImageBlob($pdfBuffer);
        $im->setBackgroundColor(new ImagickPixel("white"));
        $im->setImageBackgroundColor(new ImagickPixel("white"));
        $im->setCompressionQuality(90);
        $im->setCompression(0);
        $im->setAntiAlias(true);

        if ($multiImage) {
            $filename = $this->imageDirectory . '/' . $filetitle . '_%NUMPAGE%' . '.jpg';
            //Didn't know this was even possible xD
            foreach($im as $i => $im)
            {
                /** @var $im Imagick */
                $im->resizeImage(1000, 1000, Imagick::FILTER_GAUSSIAN, 0.9, true);
                $im->writeImage(str_replace('%NUMPAGE%', ($i+1), $filename));
                $filenames[]= str_replace('%NUMPAGE%', ($i+1), $filename);
            }
            $im->clear();
        } else {
            $im->resetIterator();
            $jointIm = $im->appendImages(true);
            $jointIm->writeImage($filename);
            $filenames []= $filename;
            $jointIm->clear();
            $jointIm->destroy();
        }

        $im->clear();
        $im->destroy();

        return $filenames;
    }
}