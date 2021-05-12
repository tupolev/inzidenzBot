<?php


namespace App\render;


use Imagick;

class ImageRenderer
{

    final public function generateFromPdf(string $pdfPath): string
    {
        //TODO make it work
        $im = new imagick($pdfPath);
        $im->setImageFormat( "jpg" );
        $img_name = time().'.jpg';
        $im->setSize(800,600);
        $im->writeImage($img_name);
        $im->clear();
        $im->destroy();

        return $img_name;
    }
}