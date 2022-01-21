<?php

namespace Mhmmdq\EcommerceApi;

class BarCode {

    public const CODE128A_START_BASE = 103;
    public const CODE128B_START_BASE = 104;
    public const CODE128C_START_BASE = 105;
    public const STOP = 106;
    public function __construct( string $barcode )
    {
        echo $this->PngToBase64( $barcode );
    }

    public function code128BarCode ( $code , $density = 1 ) {

        $code128_bar_codes = array(212222, 222122, 222221, 121223, 121322, 131222, 122213, 122312, 132212, 221213, 221312, 231212, 112232, 122132, 122231, 113222, 123122, 123221, 223211, 221132, 221231,213212, 223112, 312131, 311222, 321122, 321221, 312212, 322112, 322211, 212123, 212321, 232121, 111323, 131123, 131321, 112313, 132113, 132311, 211313, 231113, 231311,112133, 112331, 132131, 113123, 113321, 133121, 313121, 211331, 231131, 213113, 213311, 213131, 311123, 311321, 331121, 312113, 312311, 332111, 314111, 221411, 431111,111224, 111422, 121124, 121421, 141122, 141221, 112214, 112412, 122114, 122411, 142112, 142211, 241211, 221114, 413111, 241112, 134111, 111242, 121142, 121241, 114212,124112, 124211, 411212, 421112, 421211, 212141, 214121, 412121, 111143, 111341, 131141, 114113, 114311, 411113, 411311, 113141, 114131, 311141, 411131, 211412, 211214,211232, 23311120);
        $width = (((11 * strlen($code)) + 35) * ($density/72));
        $height = ($width * .15 > .7) ? $width * .15 : .7;
        $px_width = round($width * 72);
        $px_height = ($height * 72);
        $img = imagecreatetruecolor($px_width, $px_height);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagefill($img, 0, 0, $white);
        imagesetthickness($img, $density);
        $checksum = self::CODE128A_START_BASE;
        $encoding = array($code128_bar_codes[self::CODE128A_START_BASE]);
        for($i = 0; $i < strlen($code); $i++) {
            $checksum += (ord(substr($code, $i, 1)) - 32) * ($i + 1);
            array_push($encoding, $code128_bar_codes[(ord(substr($code, $i, 1))) - 32]);
        }
        array_push($encoding, $code128_bar_codes[$checksum%103]);
        array_push($encoding, $code128_bar_codes[self::STOP]);
        $enc_str = implode($encoding);
        for($i = 0, $x = 0, $inc = round(($density/72) * 100); $i < strlen($enc_str); $i++) {
            $val = intval(substr($enc_str, $i, 1));
            for($n = 0; $n < $val; $n++, $x+=$inc) { if($i%2 == 0) imageline($img, $x, 0, $x, $px_height, $black); }
        }
        return $img;
    }

    public function PngToBase64( $barcode ) 
    {
        ob_start();
        imagepng($this->code128BarCode( $barcode ));
        return "data:image/png;base64," . base64_encode(ob_get_clean());
    }

}

