<?php
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/11/21
 * Time: 下午3:27
 */
class model extends mysqlPDO
{
    protected $table = '';

    public function __construct($table = false)
    {
        parent::__construct();
        $this->table = $table ? C('DB_PREFIX') . $table : '';
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data['name'] : null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function query($sql,$batch=false){
        $prefix = C('DB_PREFIX');
        $sql = preg_replace_callback('/__([A-Z0-9_-]+)__/sU',function ($match) use($prefix){
            return $prefix.strtolower($match[1]);
        },$sql);
        return parent::query($sql,$batch);
    }

    /**  写操作
     * @param $sql
     * @param bool $batch
     * @return mixed
     */
    public function exec($sql, $batch=false){
        return $this->query($sql,$batch)->rowCount();
    }

    /** 自动化插入
     * @param bool $batch
     * @return bool|mixed
     */
    public function add($batch=false){
        $fields = $batch? array_keys($this->data[0]) : array_keys($this->data);
        $sql = "INSERT INTO $this->table(".implode(',',$fields).")VALUES(:".implode(',:',$fields).")";
        return parent::query($sql,$batch)?$this->lastInsetId() : false;
    }

    public function save($key='id'){
        $fields = array_keys($this->data);
        unset($fields[$key]);
        $each = array();
        foreach ($fields as $v){
            $each[] = "$v=:$v";
        }
        $sql = "UPDATE $this->table SET ".implode(',',$each)." WHERE $key=:$key";
        return $this->exec($sql);
    }
    public function exists($field,$value){
        return (bool)$this->data(array($field=>$value))->fetchRow("SELECT 1 FROM {$this->table} WHERE $field=:$field");
    }
}