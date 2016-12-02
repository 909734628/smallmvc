<?php
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/30
 * Time: 下午9:07
 */
class goodsController extends controller
{
  public function indexAction(){
      $cid = isset($_GET['cid'])?$_GET['cid']:-1;
      $page = isset($_GET['page'])?$_GET['page']:1;
      $data = array();
      $Category = D("Category");
      $data['category'] = $Category->getData('_tree');
      $cids = ($cid>0)?$Category->getSubIds($cid):$cid;
      $data['goods'] = D('goods')->getData('goods',$cids,$page);
  }
}