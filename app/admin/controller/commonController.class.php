<?php

/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/28
 * Time: 下午9:37
 */
class commonController extends controller
{
    protected $user = array();
    public function __construct()
    {
        parent::__construct();
        $this->checkLogin();
    }

    private function checkLogin()
    {
        if(isset($_SESSION['admin'])){
            $this->user = $_SESSION['admin'];
        } else {
            $this->redirect('http://localhost/shop/index.php?p=admin&c=login');
        }
    }
}