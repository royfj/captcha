<?php

namespace Royfj\Captcha;

class Builder {
    /**
     * Random factor
     * @var string
     */
    private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
    /**
     * Captcha code.
     * @var string
     */
    private $code;

    /**
     * Captcha code length.
     * @var int
     */
    private $codelen = 4;

    /**
     * Image  width.
     * @var int
     */
    private $width = 240;

    /**
     * Image height.
     * @var int
     */
    private $height = 120;

    /**
     * Image handler.
     * @var resource
     */
    private $img;//图形资源句柄

    /**
     * Specified font for image.
     * @var string
     */
    private $font;

    /**
     * Image font color.
     * @var int
     */
    private $fontColor;

    /**
     * Min font size for the image.
     * @var int
     */
    protected $minFontSize = 30;

    /**
     * Max font size for the image.
     * @var int
     */
    protected $maxFontSize = 40;

    /**
     * Captcha constructor.
     * @param int $width
     * @param int $height
     * @param int $minFontSize
     * @param int $maxFontSize
     */
    public function __construct($width = 240, $height = 120, $minFontSize = 40, $maxFontSize = 60) {
        $this->font = dirname(__FILE__).'/fonts/t'.mt_rand(1, 9).'.ttf';
        $this->width = $width;
        $this->height = $height;
        $this->minFontSize = $minFontSize;
        $this->maxFontSize = $maxFontSize;

        $this->createCode();
    }

    /**
     * Create a Captcha code.
     */
    private function createCode() {
        $_len = strlen($this->charset)-1;
        for ($i=0;$i<$this->codelen;$i++) {
            $this->code .= $this->charset[mt_rand(0,$_len)];
        }
    }

    /**
     * Create the background for the image.
     */
    private function createBg() {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
        imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
    }

    /**
     * Create the text.
     */
    private function createFont() {
        $_x = $this->width / $this->codelen;
        for ($i=0;$i<$this->codelen;$i++) {
            $this->fontColor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imagettftext($this->img, mt_rand($this->minFontSize, $this->maxFontSize),mt_rand(-30,30), $_x * $i + mt_rand(1,15),$this->height / 1.4,$this->fontColor,$this->font,$this->code[$i]);
        }
    }

    /**
     * create lines and snowflakes for interference.
     */
    private function createLine() {
        // Lines
        for ($i=0;$i<6;$i++) {
            $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
        }
        // Snowflakes
        for ($i=0;$i<100;$i++) {
            $color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
            imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
        }
    }

    /**
     * Output as an image.
     */
    public function output()
    {
        if (ob_get_length()) ob_clean();

        $this->createBg();
        //$this->createCode();
        $this->createLine();
        $this->createFont();

        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    /**
     * Get Captcha code.
     *
     * @return string
     */
    public function getCode() {
        return strtolower($this->code);
    }

}