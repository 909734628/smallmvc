<?php

/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/28
 * Time: 下午9:47
 */
class categoryModel extends model
{
    public function getData($callback = false)
    {
        static $data = null;
        $data or $data = $this->fetchAll("SELECT * FROM __CATEGORY__");
        return $callback? $this->$callback($data) : $data;
    }
    private function _tree($arr,$pid=0,$level=0){
        static $tree = array();
        foreach ($arr as $v){
            if($v['pid']==$pid){
                $v['level'] = $level;
                $tree[$v['id']] = $v;
                $this->_tree($arr,$v['id'],$level+1);
            }
        }
        return $tree;
    }
    public function getSubIds($pid){
        $data = $this->_tree($this->getData(),$pid);
        $result = array($pid);
        foreach($data as $v){
            $result[] = $v['id'];
        }
        return $result;
    }
}