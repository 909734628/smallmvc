<?php
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/27
 * Time: 下午8:28
 * @property captcha Captcha
 */
class loginController extends controller
{
    public function indexAction(){
        require ACTION_VIEW;
    }
    public function captchaAction(){
        $Captcha = new captcha();
        $Captcha->outPut();
    }
    public function logoutAction(){
        unset($_SESSION['admin']);
        empty(S_SESSION) or session_destroy();
        $this->redirect('/?p=admin&c=login');
    }
    public function loginExecAction(){
        if(!$this->_checkCaptcha($_POST['captcha'])){
            $this->error('登录失败，验证码错误');
        }
        $data = D('admin')->checkLogin($_POST['username'],$_POST['password']);
        $data or $this->error('登录失败，用户名或密码错误');
        //登录成功
        $_SESSION['admin'] = $data;
        $this->success('','http://localhost/shop/index.php?p=admin');
    }


    private function _checkCaptcha($captcha)
    {
        return captcha::verify($captcha);
    }
}