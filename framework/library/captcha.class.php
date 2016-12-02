<?php
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/27
 * Time: 下午9:34
 */
class captcha
{
    private $width;
    private $height;
    private $codeNum;
    private $disturbColorNum;
    private $checkCode;
    private $image;

    function __construct($width=80,$height=20,$codeNum=4)
    {
        $this->width = $width;
        $this->height = $height;
        $this->codeNum = $codeNum;
        $this->disturbColorNum = floor($width*$height/15);
        $this->checkCode = $this->createCheckCode();
    }

    /** 对外输出图像
     *
     */
    function outPut(){
        $_SESSION['code'] = strtolower($this->checkCode);
        $this->outImg();
    }

    private function outImg()
    {
        $this->getCreateImage();
        $this->setDisturbColor();
        $this->outputText();
        $this->outputImage();
    }

    private function getCreateImage()
    {
        $this->image = imagecreatetruecolor($this->width,$this->height);
        $backColor = imagecolorallocate($this->image,rand(225,255),rand(225,255),rand(225,255));
        @imagefill($this->image,0,0,$backColor);

    }
    private function createCheckCode(){
        $code = '3456789abcdefghijkmnpqrstuwxyABCDEFGHIJKLMNPQRSTUVWXY';
        $ascii = '';
        for($i=0;$i<$this->codeNum;$i++){
            $char = $code[rand(0,strlen($code)-1)];
            $ascii .= $char;
        }
        return $ascii;
    }
    private function setDisturbColor(){
        for($i=0;$i<$this->disturbColorNum;$i++){
            $color = imagecolorallocate($this->image,rand(128,255),rand(128,255),rand(128,255));
            imagesetpixel($this->image,rand(1,$this->width-1),rand(1,$this->height-1),$color);
        }
        for($i=0;$i<$this->disturbColorNum/10;$i++){
            $color = imagecolorallocate($this->image,rand(128,255),rand(128,255),rand(128,255));
            imageline($this->image,rand(1,$this->width-1),rand(1,$this->height-1),rand(1,$this->width-1),rand(1,$this->height-1),$color);
        }
    }
    private function outputText(){
        for($i=0;$i<$this->codeNum;$i++){
            $color = imagecolorallocate($this->image,rand(0,128),rand(0,128),rand(0,128));
            $fontsize = rand(3,5);
            $x = floor($this->width/$this->codeNum)*$i+3;
            $y = rand(0,$this->height-imagefontheight($fontsize));
            imagechar($this->image,$fontsize,$x,$y,$this->checkCode[$i],$color);
        }
    }
    private function outputImage(){
        if(imagetypes() & IMG_GIF){
            header('Content-type:image/gif');
            imagegif($this->image);
        } else if(imagetypes()&IMG_JPEG){
            header('Content-type:image/jpeg');
            imagejpeg($this->image,0.5);
        } else if(imagetypes()&IMG_PNG){
            header('Content-type:image/png');
            imagepng($this->image);
        } else {
            die('图片格式不支持');
        }
    }
    function __destruct()
    {
        imagedestroy($this->image);
    }

    public static function verify($captcha)
    {
        if ($_SESSION['code']===strtolower($captcha)){
            return true;
        } else {
            return false;
        }
    }
}