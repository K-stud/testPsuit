<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class Image extends ActiveRecord {
    public $file;
    public $user_file_name;
    
    public static function tableName()
    {
        return 'images';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPath()
    {
        return $this->file_path;
    }

    public function getName()
    {
        return $this->file_name;
    }

    public function getUploaderId()
    {
        return $this->user_id;
    }

    public function getUploaderUsername()
    {
        return $this->user_name;
    }

    public function getTimeModify()
    {
        return $this->time_modify;
    }
}