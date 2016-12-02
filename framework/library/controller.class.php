<?php
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/21
 * Time: 下午3:15
 */
class controller
{
  public function __call($name, $arguments)
  {
      E('您访问的操作不存在!');
  }
  protected function redirect($url){
      header("Location:$url");
      exit;
  }

  protected function success($msg='',$target=''){
      $this->ajaxReturn(array('ok'=>true,'msg'=>$msg,'target'=>$target));
  }

  protected function error($msg='',$target=''){
      $this->ajaxReturn(array('ok'=>false,'msg'=>$msg,'target'=>$target));
  }

  protected function ajaxReturn($data){
      exit(json_encode($data));
  }
}