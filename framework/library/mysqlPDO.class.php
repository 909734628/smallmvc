<?php
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/17
 * Time: 下午3:57
 */
class mysqlPDO
{
    protected static $db = null;
    protected $data = array();

    function __construct()
    {
        isset(self::$db) or self::_connect();
    }
    private function __clone(){}
    private static function _connect()
    {
        $config = C('DB_CONFIG');
        $dsn = "{$config['db']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
        try {
            self::$db = new PDO($dsn,$config['user'],$config['pass']);
            self::$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            E('数据库连接失败: '.$e->getMessage());
        }
    }
    public function query($sql,$batch=false){
         $data = $batch? $this->data:array($this->data);
         $this->data = array();
         $stmt = self::$db->prepare($sql);
         foreach ($data as $v){
            if($stmt->execute($v)===false){
                exit('数据库操作失败'.implode('-',$stmt->errorInfo()));
            }
        }
         return $stmt;
    }
    public function data($data){
        $this->data = $data;
        return $this;
    }
    public function fetchRow($sql){
        return $this->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll($sql){
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lastInsetId(){
        return self::$db->lastInsertId();
    }

    public function fetchColumn($sql){
        return $this->query($sql)->fetchColumn();
    }

}