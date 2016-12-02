<?php
function E($msg){
    header('content-type:text/html;charset=utf-8');
    die('<pre>'.htmlspecialchars($msg).'</pre>');
}

function C($name,$value=null){
    static $config = null;
    if(!$config){
        $config = require(COMMON_PATH.'config.php');
    }
    if($value==null){
        return isset($config[$name])? $config[$name]:'';
    } else {
        $config[$name] = $value;
    }

}

function D($name) {
    static $Model = array();
    $name = strtolower($name);
    if(!isset($Model[$name])){
        $model_name = $name.'Model';
        $Model[$name] = new $model_name($name);

    }
    return $Model[$name];
}
function M($name=''){
    static $Model = array();
    $name = strtolower($name);
    if(!isset($Model[$name])){
        $Model[$name] = new model($name);
    }
    return $Model[$name];
}
function password($password,$salt){
    return md5(md5($password).$salt);
}
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/21
 * Time: 下午2:51
 */