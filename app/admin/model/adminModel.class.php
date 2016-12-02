<?php

/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/28
 * Time: 下午8:07
 */
class adminModel extends model
{
   public function checkLogin($username,$password){
       $this->data['username']=$username;
       $data = $this->fetchRow("SELECT id,username,password,salt FROM __ADMIN__ WHERE username=:username");
       if($data && $data['password']==password($password,$data['salt'])){
           return array('id'=>$data['id'],'name'=>$data['username']);
       }
   }

}