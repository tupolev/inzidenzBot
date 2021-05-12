<?php


namespace App\render;


use Imagick;
use ImagickPixel;

class ImageRenderer
{
    private string $imageDirectory;

    /**
     * ImageRenderer constructor.
     * @param string $imageDirectory
     */
    public function __construct(string $imageDirectory)
    {
        $this->imageDirectory = $imageDirectory;
    }

    final public function generatePngFromPdfBuffer(string $pdfBuffer, string $filetitle): string
    {
        //TODO fix blurry fonts and image size
        $im = new imagick();
        $im->setResolution(350,350);
        $im->readImageBlob($pdfBuffer);
        $im->setBackgroundColor(new ImagickPixel("white"));
        $im->setImageBackgroundColor(new ImagickPixel("white"));
        $im->setCompressionQuality(90);
        $im->setCompression(0);
        $im->setAntiAlias(true);
//        $im->cropImage(1800,1800,0,0);
//        $im->resizeImage(600,600, Imagick::FILTER_BOX, 0, false);
//        $im->setFormat("jpeg");
        $filename = $this->imageDirectory . '/' . $filetitle.'.jpg';
        $im->writeImage($filename);
        $im->clear();
        $im->destroy();

//        file_put_contents(sys_get_temp_dir() . '/'. $filetitle.'.pdf', $pdfBuffer);
//        $pathmods = "MAGICK_CONFIGURE_PATH='/home/vagrant/.config/imagick/:/etc/ImageMagick-6/'";
//        exec($pathmods . ' convert "'. sys_get_temp_dir() . '/'. $filetitle.'.pdf' .'" -colorspace RGB -resize 800 "'.
//            $filename
//            .'"', $output, $response);

        return $filename;
    }
}