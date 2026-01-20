<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class ApiController extends Controller
{
    public function actionTest($msg='По умолчанию'){
        echo('API работает '.$msg);
    }

    // Плейсзолдер для будущего API
}