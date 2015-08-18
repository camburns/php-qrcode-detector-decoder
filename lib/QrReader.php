<?php

namespace Zxing;

include_once ('common/customFunctions.php');

use Zxing\Common\HybridBinarizer;
use Zxing\Qrcode\QRCodeReader;

abstract class QrReader
{
    public $result;

    public function getLuminanceSource($imageFileName)
    {
        if (extension_loaded('imagick')) {
            $im = new \Imagick();
            $im->readImage($imageFileName);
            $width = $im->getImageWidth();
            $height = $im->getImageHeight();

            return new IMagickLuminanceSource($im, $width, $height);
        }

        $image = file_get_contents($imageFileName);
        $sizes = getimagesize($imageFileName);
        $width = $sizes[0];
        $height = $sizes[1];
        $im = imagecreatefromstring($image);

        return new GDLuminanceSource($im, $width, $height);
    }

    public function decode(LuminanceSource $source)
    {
        try {
            $histo = new HybridBinarizer($source);
            $bitmap = new BinaryBitmap($histo);
            $reader = new QRCodeReader();

            $this->result = $reader->decode($bitmap);
        } catch (NotFoundException $er) {
            $this->result = false;
        } catch(FormatException $er) {
            $this->result = false;
        } catch(ChecksumException $er) {
            $this->result = false;
        }

        if (method_exists($this->result,'toString')) {
            return ($this->result->toString());
        }

        return $this->result;
    }
}

