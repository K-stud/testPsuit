<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\rest\DeleteAction;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use app\models\Image;
use yii\web\Response;
use yii\db\Expression;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;

class ApiController extends ActiveController
{
    public $modelClass = 'app\models\Image';

    public function beforeAction($action)
    {
        $user = Yii::$app->user->identity;
            if ($user) {
                Yii::info("Пользователь найден: " . $user->username);
            } else {
                Yii::info("Пользователь не найден!");
            }
        return parent::beforeAction($action);
    }

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
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        $behaviors['authenticator'] = [
        'class' => HttpBearerAuth::class,
        ];

        $behaviors['access'] = [
        'class' => AccessControl::class,
        'only' => ['gallery'],
        'rules' => [
            [
                'actions' => ['gallery'],  
                'allow' => true,           
                'roles' => ['@'],        
            ],
        ],
        'denyCallback' => function($rule, $action) {
        throw new \yii\web\ForbiddenHttpException('Доступ запрещен');
        },
    ];
        return $behaviors;
    }

    public function actionGallery()
    {
        $provider = new ActiveDataProvider([
            'query' => Image::find(),
            'pagination' => ['pageSize' => 20],
        ]);

        return $provider->getModels();
    }

    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Image();
        $model->file = UploadedFile::getInstanceByName('image');
        $model->user_file_name = Yii::$app->request->post('user_file_name');

        if ($model->file instanceof UploadedFile) {
            $timestamp = time();
            $hash = md5($model->file->baseName . $timestamp);
            $trueFileName = $timestamp.'_'.$hash.'_'.$model->user_file_name.'.'.$model->file->extension;
            $path = Yii::getAlias('@webroot/uploads/') . $trueFileName;

            if ($model->file->saveAs($path)) {
                $model->file_name = $model->user_file_name;
                $model->file_true_name = $trueFileName;
                $model->file_path = '/uploads/'.$trueFileName;
                $model->time_modify = date('Y-m-d H:i:s');
                $model->save(false); // Сохраняем в базу, без валидации для простоты
                return $this->asJson(['success' => true, 'filename' => $trueFileName, 'message' => 'Изображение успешно загружено',]);
            }
            return $this->asJson([
                'success' => false,
                'message' => 'Ошибка при загрузке изображения',
            ]);
        }
        return ['success' => false];
    }

    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $params = Yii::$app->request->getBodyParams(); // вот правильный способ
        $id = $params['id'] ?? null;

        if (!$id) {
            return ['success' => false, 'message' => 'ID не передан'];
        }

        $model = Image::findOne($id);

        if (!$model) {
            return ['success' => false, 'message' => 'Not found'];
        }


        // Не удаляет
        $path = Yii::getAlias('@webroot/uploads/') . $model->true_file_name;

        if (file_exists($path)) {
            unlink($path);
        }

        $model->delete();

    return ['success' => true, 'message' => 'Изображение удалено'];
}

}