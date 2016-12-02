<?php

/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/20
 * Time: 下午8:07
 */
class categoryModel extends model
{

  public function getData()
  {
      return $this->fetchAll('SELECT * FROM itcase_shop.shop_category');
  }
  public function addData($name,$pid){
      $this->data['name'] = $name;
      $this->data['pid'] = $pid;
      $this->query('INSERT INTO itcase_shop.shop_category(name,pid) VALUES (:name,:pid)');
      return $this->lastInsetId();
  }
}