<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $password;
    public $repeat_password;

    /**
     * @return array правил валидации
     */
    public function rules()
    {
        return [
            [['username', 'password', 'repeat_password'], 'required'],
            ['username', 'string', 'min' => 3, 'max' => 30],
            ['username', 'validateUsername'],
            ['repeat_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли должны совпадать.'],
        ];
    }

    /**
     * Регистрация пользователя / Создание записи в БД
     * 
     * @return array
     */
    public function register()
    {
        if(!$this->validate())
            return false;

        $user = new User();
        $user->user_name = $this->username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->auth_key = Yii::$app->security->generateRandomString(64);
        $user->access_token = Yii::$app->security->generateRandomString(64);
        
        // Добавить метод проверки успешности операции
        return $user->save();
    }


    /**
     * Проверяет свободен ли логин
     * 
     * @return bool 
     */
    public function validateUsername()
    {
        if (User::find()->where(['user_name' => $this->username])->exists()) {
            $this->addError('username', 'Логин занят.');
            return false;
        }
        return true;
    }

    /**
     * Наименования атриботов полей регистрации
     * 
     * @return array 
     */
    public function attributeLabels()
         {
            return [
                'username' => 'Логин',
                'password' => 'Пароль',
                'repeat_password' => 'Повторите пароль',
            ];
        }
}
