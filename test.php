<?php
require "framework/library/fileUpLoad.class.php";
require "framework/library/image.class.php";
$up = new fileUpLoad();
$image = new image();
$up->set('path','./image')->set('size',2000000)->set('allowtype',array('gif','jpg','png'))->set('israndname',true);
if($up->upload('myfile')){
    print_r($up->getFileName());
    foreach ($up->getFileName() as $name){
        $image->setPath('./image')->thumb($name,500,500);
    }
} else {
    print_r($up->getErrorMsg());
}
