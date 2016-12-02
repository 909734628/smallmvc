<?php
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/20
 * Time: 下午9:06
 */
class framework
{
  public static function run(){
      self::init();
      self::registerAutoLoad();
      require FRAMEWORK_PATH.'function.php';
      self::dispatch();
  }

    private static function init()
    {
        define('DS',DIRECTORY_SEPARATOR);
        define('ROOT',getcwd().DS);
        define('APP_PATH',ROOT.'app'.DS);
        define('FRAMEWORK_PATH',ROOT.'framework'.DS);
        define('LIBRARY_PATH',FRAMEWORK_PATH.'library'.DS);
        define('PUBLIC_PATH',ROOT.'public'.DS);
        define('COMMON_PATH',APP_PATH.'common'.DS);
        list($p,$c,$a) = self::getParams();
        define('PLATFORM',strtolower($p));
        define('CONTROLLER',strtolower($c));
        define('ACTION',strtolower($a));
        define('PLATFORM_PATH',APP_PATH.PLATFORM.DS);
        define('CONTROLLER_PATH',PLATFORM_PATH.'controller'.DS);
        define('MODEL_PATH',PLATFORM_PATH.'model'.DS);
        define('VIEW_PATH',PLATFORM_PATH.'view'.DS);

        define('COMMON_VIEW',VIEW_PATH.'common'.DS);
        define('ACTION_VIEW',VIEW_PATH.CONTROLLER.DS.ACTION.'.html');
        session_start();
    }

    private static function registerAutoLoad()
    {
       spl_autoload_register(function ($class_name){
          if(strpos($class_name,'Controller')){
              $target = CONTROLLER_PATH."$class_name.class.php";
              if(is_file($target)){
                  require $target;
              } else {
                  exit('您访问的参数有误!');
              }
          } else if(strpos($class_name,'Model')){
               require MODEL_PATH."$class_name.class.php";
           } else {
              require(LIBRARY_PATH."$class_name.class.php");
          }
       });
    }

    private static function dispatch()
    {
        $c = CONTROLLER."Controller";
        $a = ACTION."Action";
        $controller = new $c();
        $controller->$a();
    }
    private static function getParams(){
        $p = isset($_GET['p'])? $_GET['p']:'home';
        $c = isset($_GET['c'])? $_GET['c']:'index';
        $a = isset($_GET['a'])? $_GET['a']:'index';
        return array($p,$c,$a);
    }
}