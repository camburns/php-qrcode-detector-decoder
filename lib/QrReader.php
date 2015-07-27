<?php

namespace Zxing;

include_once ('common/customFunctions.php');

use Zxing\Common\HybridBinarizer;
use Zxing\Qrcode\QRCodeReader;

abstract class QrReader
{
    public $result;

    public function decode($filename)
    {
        try {
            if (extension_loaded('imagick')) {
                $im = new \Imagick();
                $im->readImage($filename);
                $width = $im->getImageWidth();
                $height = $im->getImageHeight();
                $source = new IMagickLuminanceSource($im, $width, $height);
            } else {
                $image = file_get_contents($filename);
                $sizes = getimagesize($filename);
                $width = $sizes[0];
                $height = $sizes[1];
                $im = imagecreatefromstring($image);

                $source = new GDLuminanceSource($im, $width, $height);
            }
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

