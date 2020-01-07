<?php
/**
 * @link http://www.euqol.com/
 * @copyright Copyright (c) 2015 Su thyseus
 * @license http://www.euqol.com/license/
 */

namespace thyseus\auth0\controllers;

use thyseus\auth0\models\LoginForm;
use Yii;

/**
 * This is the controller class for the User model.
 * @author Su anli <anli@euqol.com>
 * @since 1.0.0
 */
class UserController extends \yii\web\Controller
{

    /**
     * Login a user with auth0
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout = '@vendor/thyseus/yii2-metronic/views/layouts/login';

        $model = new LoginForm;

        $auth0 = $this->module->auth0;

        if ($auth0->getUser() && $auth0->validate()) {
            $model->login();
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout a user with auth0
     * @return mixed
     */
    public function actionLogout()
    {
        $this->module->auth0->logout();
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
