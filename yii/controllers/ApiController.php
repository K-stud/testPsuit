<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\UploadedFile;
use app\models\Image;
use yii\web\Response;
use yii\db\Expression;

class ApiController extends ActiveController
{
    public $modelClass = 'app\models\Image';

    public function actions()
    {
        $actions = parent::actions();
        
        // Отключаем create, чтобы сделать свой.
        unset($actions['create']);
        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['multipart/form-data'] = Response::FORMAT_JSON;
        // Обновеление timestamp при изменении данных.
        $behaviors[] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'time_modify',
            'updatedAtAttribute' => 'time_modify',
            'value' => new Expression('NOW()'),
        ];
        return $behaviors;
    }

    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Image();
        $model->file_name = UploadedFile::getInstanceByName('file');

        if($model->file_name && $model->validate()) {
            $fileTrueName = 
        }
    }
}