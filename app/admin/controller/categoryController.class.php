<?php

/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/28
 * Time: 下午9:43
 */
class categoryController extends controller
{
    public function indexAction()
    {
//        $data = D('category')->getData('_tree');
        $currentPage = $_GET['page']?$_GET['page']:1;
        $total = D('category')->fetchColumn("SELECT COUNT(*) FROM __CATEGORY__");
        $page = new page($total,2,$currentPage);

        $data = D('category')->fetchAll("SELECT * FROM __CATEGORY__ LIMIT ".$page->getLimit());
        $pageShow = $page->showPage();
        require ACTION_VIEW;
    }
    public function addAction(){
        $id = isset($_GET['id'])?$_GET['id']:0;
        $data = D('category')->getData('_tree');
        require ACTION_VIEW;
    }
    public function addExecAction(){
        $pid = $_POST['pid'];
        $name = $_POST['name'];
        $id = M('category')->data(array('pid'=>$pid,'name'=>$name))->add();
        if(isset($_POST['return'])){
            $this->success('',"http://localhost/shop/index.php?p=admin&c=category");
        } else {
            $this->success('',"http://localhost/shop/index.php?p=admin&c=category&a=add&id=$pid");
        }
    }
    public function editAction(){
        $id = isset($_GET['id'])?$_GET['id']:0;
        $data = D('category')->getData('_tree');
        isset($data[$id]) or E('error category');
        require ACTION_VIEW;

    }
    public function editExecAction(){
        $id = $_POST['id'];
        $pid = $_POST['pid'];
        $name = $_POST['name'];
        $Category = D('category');

        if(!in_array($pid,$Category->getSubIds($id))){
            $this->error('error');
        }
        $Category->data(array('id'=>$id,'name'=>$name,'pid'=>$pid))->save();
        if(isset($_POST['return'])){
            $this->success('',"http://localhost/shop/index.php?p=admin&c=category");
        } else {
            $this->success('',"http://localhost/shop/index.php?p=admin&c=category&a=edit&id=$pid");
        }
    }


}