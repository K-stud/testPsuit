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
            ['repeat_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли должны совпадать.'],
        ];
    }

    /**
     * Регистрация пользователя / Создание записи в БД
     * 
     * @return array правил валидации
     */
    public function register()
    {
        if(!$this->validate())
            return false;

        $user = new User();
        $user->user_name = $this->username;
        $user->password_hash = Yii::$app->security->generatePassowrdHash($this->password);
        $user->auth_key =
        $user->generateAccessToken();
        
        return $user->save();
    }


    /**
     * Проверяет свободен ли логин
     * 
     * @return bool 
     */
    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        // Проверка логина
        if (User::find()->where(['username' => $this->username])->exists()) {
            $this->addError('username', 'Такой логин уже занят.');
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
                'rememberMe' => 'Запомнить меня',
            ];
        }
}
