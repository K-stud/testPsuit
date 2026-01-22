<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use app\models\Image;
use yii\web\Response;
use yii\db\Expression;
use yii\filters\auth\QueryParamAuth;

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
        'class' => QueryParamAuth::class,
        'tokenParam' => 'access-token', // GET-параметр
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
}