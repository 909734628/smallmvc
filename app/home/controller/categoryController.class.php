<?php

/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/20
 * Time: 下午8:26
 */
class categoryController extends controller
{
    public function indexAction()
    {
//        $Category = new categoryModel();
//        $data = $Category->getData();
//        require ACTION_VIEW;
        $Category = M('category');
        $date = $Category->fetchAll('SELECT * FROM __CATEGORY__');
        if ($Category->exists('name', 'phone')) {
            echo 'phone exists';
        }
    }

    public function addAction()
    {
        $Category = M('category');
        $result = $Category->data(array(array('name' => 'computer', 'pid' => 0), array('name' => 'fruit', 'pid' => 0)))->add(true);
        if ($result) {
            echo '添加成功';
        }
    }

    public function editAction()
    {
        $Category = M('category');
        $result = $Category->data(array('id' => 1, 'name' => 'foods'))->save();
        if ($result) {
            echo '修改成功';
        }
    }

    public function delAction()
    {

    }
}