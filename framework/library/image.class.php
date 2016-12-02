<?php
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/12/2
 * Time: 上午10:16
 */
class image
{
    private $path; //图片保存路径

    function __construct($path = './')
    {
        $this->path = $path;
    }

    /**
     * @param $name (需要处理的图片名称)
     * @param $width
     * @param $height
     * @param string $qz
     * @return bool
     */

    public function thumb($name, $width, $height, $qz='th_'){
        $imgInfo = $this->getInfo($name);
        $srcImg = $this->getImg($name,$imgInfo);
        $size = $this->getNewSize($width,$height,$imgInfo);
        $newImg = $this->kidOfImage($srcImg,$size,$imgInfo);
        return $this->createNewImage($newImg,$qz.$name,$imgInfo);
    }


    private function getInfo($name)
    {
        $spath = rtrim($this->path,'/').'/';
        $data = getimagesize($spath.$name);
        $imageInfo['width'] = $data[0];
        $imageInfo['height'] = $data[1];
        $imageInfo['type'] = $data[2];
        return $imageInfo;
    }

    private function getImg($name, $imgInfo)
    {
        $spath = rtrim($this->path,'/').'/';
        $srcPic = $spath.$name;
        switch ($imgInfo['type']){
            case 1:
                $img = imagecreatefromgif($srcPic);
                break;
            case 2:
                $img = imagecreatefromjpeg($srcPic);
                break;
            case 3:
                $img = imagecreatefrompng($srcPic);
                break;
            default:
                return false;
            break;
        }
        return $img;
    }

    private function getNewSize($width, $height, $imgInfo)
    {
        $size['width'] = $imgInfo['width'];
        $size['height'] = $imgInfo['height'];
        if($width<$imgInfo['width']){
            $size['width'] = $width;
        }
        if($height<$imgInfo['height']){
            $size['height'] = $height;
        }
        if($imgInfo['width']*$size['width']>$imgInfo['height']*$size['height']){
            $size['height'] = round($imgInfo['height']/$imgInfo['width']*$size['width']);
        } else {
            $size['width'] = round($imgInfo['width']/$imgInfo['height']*$size['height']);
        }
        return $size;
    }

    private function kidOfImage($srcImg, $size, $imgInfo)
    {
        $newImg = imagecreatetruecolor($size['width'],$size['height']);
        $otsc = imagecolortransparent($srcImg);
        if($otsc>=0&&$otsc<imagecolorstotal($srcImg)){
            $transparentcolor = imagecolorsforindex($srcImg,$otsc);
            $newtransparentcolor = imagecolorallocate($newImg,$transparentcolor['red'],$transparentcolor['green'],$transparentcolor['blue']);
            imagefill($newImg,0,0,$newtransparentcolor);
            imagecolortransparent($newImg,$newtransparentcolor);
        }
        imagecopyresampled($newImg,$srcImg,0,0,0,0,$size['width'],$size['height'],$imgInfo['width'],$imgInfo['height']);
        imagedestroy($srcImg);
        return $newImg;
    }

    private function createNewImage($newImg, $newName, $imgInfo)
    {
        $spath = rtrim($this->path,'/').'/';
        switch ($imgInfo['type']){
            case 1:
                $result = imagegif($newImg,$spath.$newName);
                break;
            case 2:
                $result = imagejpeg($newImg,$spath.$newName);
                break;
            case 3:
                $result = imagepng($newImg,$spath.$newName);
                break;
            default:
                return false;
                break;
        }
        imagedestroy($newImg);
        return $newName;
    }


    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
}