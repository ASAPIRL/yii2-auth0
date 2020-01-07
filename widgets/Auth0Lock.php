<?php
/**
 * @link      http://www.euqol.com/
 * @copyright Copyright (c) 2015 Su anli
 * @license   http://www.euqol.com/license/
 */

namespace thyseus\auth0\widgets;

use thyseus\auth0\assets\Auth0LockAsset;
use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Html;

/**
 * This is the widget class for the Auth0Lock.
 * ```php
 * echo \thyseus\auth0\widgets\Auth0Lock::widget([]);
 * ```
 *
 * @author Su anli <anli@euqol.com>
 * @since  1.0.0
 */
class Auth0Lock extends Widget
{
    /**
     * @var string
     */
    public $client_id = '';

    /**
     * @var string
     */
    public $domain = '';

    /**
     * @var boolean
     */
    public $redirect_uri = '';

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        $module = Yii::$app->getModule('auth0');

        $this->client_id = $module->client_id;
        $this->domain = $module->domain;
        $this->redirect_uri = $module->redirect_uri;

        Auth0LockAsset::register($this->getView());
        $this->getView()->registerJs($this->js);
    }

    /**
     * Renders the widget.
     *
     * @return string
     */
    public function run()
    {
        echo Html::tag('div', '', [
            'id'    => 'root',
            'style' => 'width: 280px; margin: 40px auto; padding: 10px; border-width: 1px;',
        ]);
    }

    /**
     * @return string
     */
    protected function getJs()
    {
        $rememberLastLogin = Yii::$app->getModule('auth0')->rememberLastLogin;

        return <<< JS
            var options = {
                focusInput: true,
                rememberLastLogin: {$rememberLastLogin},
                redirectUrl: '{$this->redirect_uri}',
                redirect: false, // @see https://github.com/auth0/lock#popup-mode
                responseType: 'code',
                authParams: {
                  scope: 'email openid profile'
                  }
            };

            var lock = new Auth0Lock('{$this->client_id}', '{$this->domain}', options);

            lock.show();
JS;
    }
}
