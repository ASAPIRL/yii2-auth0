<?php
/**
 * @link      http://www.euqol.com/
 * @copyright Copyright (c) 2015 Su anli
 * @license   http://www.euqol.com/license/
 */

namespace thyseus\auth0\controllers;

use thyseus\auth0\models\LoginForm;
use Yii;

/**
 * This is the controller class for the User model.
 *
 * @author Su anli <anli@euqol.com>
 * @since  1.0.0
 */
class UserController extends \yii\web\Controller
{
    /**
     * Login a user with auth0
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $auth0 = $this->module->auth0;

        if (! Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionCallback()
    {
        $auth0 = $this->module->auth0;

        $model = new LoginForm;

        if ($auth0->getUser()) {
            $model->login();
            return $this->goBack();
        }
    }

    /**
     * Logout a user with auth0
     *
     * @return mixed
     */
    public function actionLogout()
    {
        $this->module->auth0->logout();
        Yii::$app->auth0user->logout();
        return $this->goHome();
    }
}
