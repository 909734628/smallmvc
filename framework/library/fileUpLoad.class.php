<?php
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/12/1
 * Time: 下午4:08
 */
class fileUpLoad
{
    private $path = './public/upload'; //以后注意改动;
    private $allowtype = array('jpg', 'gif', 'png');
    private $maxsize = 2000000; //文件最大上传为2M；
    private $israndname = false;

    private $originName;
    private $tmpFileName;
    private $fileType;
    private $fileSize;
    private $newFileName;
    private $errorNum = 0;
    private $errorMess = '';

    public function set($key, $val)
    {
        $key = strtolower($key);
        if (array_key_exists($key, get_class_vars(get_class($this)))) {
            $this->setOption($key, $val);
        }
        return $this;
    }

    /** support series of files to upload
     * @param $fileField
     * @return bool
     */
    public function upload($fileField)
    {
        $return = true;
        if (!$this->checkFilePath()) {
            $this->errorMess = $this->getError();
            return false;
        }
        $name = $_FILES[$fileField]['name'];
        $tmp_name = $_FILES[$fileField]['tmp_name'];
        $size = $_FILES[$fileField]['size'];
        $error = $_FILES[$fileField]['error'];
        if (is_array($name)) { //多个文件上传有多个文件名
            $errors = array();
            for ($i = 0; $i < count($name); $i++) {
                if ($this->setFiles($name[$i], $tmp_name[$i], $size[$i], $error[$i])) { //表单上传文件没有问题
                    if (!$this->checkFileSize() || !$this->checkFileType()) {
                        $errors[] = $this->getError();
                        $return = false;
                    }
                } else {
                    $errors[] = $this->getError();
                    $return = false;
                }
                if (!$return) {
                    $this->setFiles();
                }
            }
            //已经检查过了上传文件都没有问题
            if ($return) {
                $fileNames = array();
                for ($i = 0; $i < count($name); $i++) {
                    if ($this->setFiles($name[$i], $tmp_name[$i], $size[$i], $error[$i])) {
                        $this->setNewFileName();
                        if (!$this->copyFile()) {
                            $errors[] = $this->getError();
                            $return = false;
                        }
                        $fileNames[] = $this->newFileName;
                    }
                }
                $this->newFileName = $fileNames;
            }
            $this->errorMess = $errors;
            return $return;
        } else {  //upload single file
           if($this->setFiles($name,$tmp_name,$size,$error)){
               if($this->checkFileSize()&&$this->checkFileType()){
                   $this->setNewFileName();
                   if($this->copyFile()){
                       return true;
                   } else {
                       $return = false;
                   }
               } else {
                   $return = false;
               }
           } else {
               $return = false;
           }
           if(!$return){
               $this->errorMess = $this->getError();
               return $return;
           }
        }
    }

    public function getFileName(){
        return $this->newFileName;
    }
    public function getErrorMsg(){
        return $this->errorMess;
    }



    private function setOption($key, $val)
    {
        $this->$key = $val;
    }

    private function checkFilePath()
    {
        if (empty($this->path)) {
            $this->setOption('errorNum', -5);
            return false;
        }
        if (!file_exists($this->path) || !is_writable($this->path)) {
            if (!@mkdir($this->path, 0755)) {
                $this->setOption('errorNum', -4);
                return false;
            }
        }
        return true;
    }

    private function getError()
    {
        $str = "上传文件{$this->originName}时出错:";
        switch ($this->errorNum) {
            case 4:
                $str .= "没有文件上传";
                break;
            case 3:
                $str .= "文件只有部分被上传";
                break;
            case 2:
            case 1:
                $str .= "上传文件大小超过了限定的值";
                break;
            case -1:
                $str .= "未允许类型";
                break;
            case -2:
                $str .= "文件过大不能超过预定字节";
                break;
            case -3:
                $str .= "上传失败";
                break;
            case -4:
                $str .= "建立存放路径失败";
                break;
            case -5:
                $str .= "必须指定上传的路径";
                break;
            default:
                $str .= "未知错误";
        }
        return $str;
    }

    private function setFiles($name = '', $tmp_name = '', $size = 0, $error = 0)
    {
        $this->setOption('errorNum', $error);
        if ($error) {
            return false;
        }
        $this->setOption('originName', $name);
        $this->setOption('tmpFileName', $tmp_name);
        $aryStr = explode('.', $name);
        $this->setOption('fileType', strtolower($aryStr[count($aryStr) - 1]));
        $this->setOption('fileSize', $size);
        return true;
    }

    private function checkFileSize()
    {
        if ($this->fileSize > $this->maxsize) {
            $this->setOption('errorNum', -2);
            return false;
        }
        return true;
    }

    private function checkFileType()
    {
        if (in_array($this->fileType, $this->allowtype)) {
            return true;
        } else {
            $this->setOption('errorNum', -1);
            return false;
        }
    }

    private function setNewFileName()
    {
        if ($this->israndname) {
            $this->setOption('newFileName', $this->proRandName());
        } else {
            $this->setOption('newFileName', $this->originName);
        }
    }

    private function proRandName()
    {
        $fileName = date('YmdHis') . '_' . rand(100, 999);
        return $fileName . '.' . $this->fileType;
    }

    /** copy the temfile to appointed path
     *
     */
    private function copyFile()
    {
        if (!$this->errorNum) {
            $path = rtrim($this->path, '/') . '/';
            $path .= $this->newFileName;
            if (@move_uploaded_file($this->tmpFileName, $path)) {
                return true;
            } else {
                $this->setOption('errorNum', -3);
                return false;
            }
        }
    }
}