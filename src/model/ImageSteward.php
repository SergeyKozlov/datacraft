<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');

use \Gumlet\ImageResize;

class ImageSteward
{
    // sudo apt-get install php7.2-gd
    function convertImage($originalImage, $outputImage, $quality)
    {
        echo "\n\rImageSteward convertImage originalImage $originalImage\n";
        echo "\n\rImageSteward convertImage outputImage $outputImage\n";
        echo "\n\rImageSteward convertImage quality $quality\n";

        // jpg, png, gif or bmp?
        $exploded = explode('.',$originalImage);
        $ext = $exploded[count($exploded) - 1];

        if (preg_match('/jpg|jpeg/i',$ext))
            $imageTmp=imagecreatefromjpeg($originalImage);
        else if (preg_match('/png/i',$ext))
            $imageTmp=imagecreatefrompng($originalImage);
        else if (preg_match('/gif/i',$ext))
            $imageTmp=imagecreatefromgif($originalImage);
        else if (preg_match('/bmp/i',$ext))
            $imageTmp=imagecreatefrombmp($originalImage);
        else
            return 0;

        // quality is a value from 0 (worst) to 100 (best)
        imagejpeg($imageTmp, $outputImage, $quality);
        imagedestroy($imageTmp);

        return 1;
    }

    public function resizeImage($resizeImage, $newImage, $width = 800)
    {
        $image = new ImageResize($resizeImage);

        //$image = new \Gumlet\ImageResize();
        $image->resizeToWidth($width);
        $image->save($newImage, IMAGETYPE_JPEG);
    }
}