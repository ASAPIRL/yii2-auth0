<?php
/**
 * @link      http://www.euqol.com/
 * @copyright Copyright (c) 2015 Su anli
 * @license   http://www.euqol.com/license/
 */

namespace thyseus\auth0\controllers;

use yii\base\Event;
use yii\helpers\Url;
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
    const EVENT_BEFORE_AUTH0_LOGIN = 'event_before_auth0_login';

    const EVENT_BEFORE_AUTH0_LOGOUT = 'event_before_auth0_logout';

    const EVENT_BEFORE_AUTH0_LOGIN_CALLBACK = 'event_before_auth0_login_callback';
    const EVENT_AFTER_AUTH0_LOGIN_CALLBACK = 'event_after_auth0_login_callback';

    /**
     * Login a user with auth0
     *
     * @return mixed
     */
    public function actionLogin()
    {
        Event::trigger(self::class, self::EVENT_BEFORE_AUTH0_LOGIN);

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
        Event::trigger(self::class, self::EVENT_BEFORE_AUTH0_LOGIN_CALLBACK);

        $auth0 = $this->module->auth0;

        $model = new LoginForm;

        if ($auth0->getUser()) {
            $model->login();
            Event::trigger(self::class, self::EVENT_AFTER_AUTH0_LOGIN_CALLBACK);
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
        Event::trigger(self::class, self::EVENT_BEFORE_AUTH0_LOGOUT);
        $module = $this->module;

        $module->auth0->logout();

        $logoutUrl = sprintf('https://%s/v2/logout?client_id=%s&returnTo=%s',
            $module->domain,
            $module->client_id,
            Url::to($module->redirect_uri_logout, true)
        );

        return $this->redirect($logoutUrl);
    }
}
