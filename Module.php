<?php
/**
 * @link      http://www.euqol.com/
 * @copyright Copyright (c) 2015 Su anli
 * @license   http://www.euqol.com/license/
 */

namespace thyseus\auth0;

use thyseus\auth0\models\Auth0;
use Yii;
use yii\helpers\Json;

/**
 * This is the main module class.
 *
 * @author Su anli <anli@euqol.com>
 * @since  1.0.0
 */
class Module extends \yii\base\Module
{
    /**
     * @var array
     */
    public $adminEmails = [];

    /**
     * @inheritdoc
     */
    public $layout = '@vendor/thyseus/yii2-auth0/views/layouts/main';

    /**
     * @var string
     */
    public $controllerNamespace = 'thyseus\auth0\controllers';

    /**
     * @var string
     */
    public $scope = '';

    /**
     * @var string
     */
    public $service_id = '';

    /**
     * @var string
     */
    public $domain = '';

    /**
     * @var string
     */
    public $client_id = '';

    /**
     * @var string
     */
    public $client_secret = '';

    /**
     * @var boolean
     */
    public $redirect_uri = '';

    /**
     * @var string
     */
    public $persist_id_token = true;

    /**
     * @var string
     */
    public $persist_access_token = true;

    /**
     * @var array
     */
    public $api_tokens = [];

    /**
     * @var string
     */
    public $rememberLastLogin = 'false';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return mixed
     */
    public function getAuth0(): Auth0
    {
        return new Auth0([
            'domain'               => $this->domain,
            'client_id'            => $this->client_id,
            'client_secret'        => $this->client_secret,
            'redirect_uri'         => $this->redirect_uri,
            'persist_id_token'     => $this->persist_id_token,
            'persist_access_token' => $this->persist_access_token,
        ]);
    }

    /**
     * @return mixed
     */
    public function getAuth0ByAccessToken($token)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $this->domain . '/userinfo');
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["authorization: Bearer $token"]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $response = Json::decode($response);

        if ('Unauthorized' != $response) {
            $auth0 = $this->getAuth0();
            $auth0->setUser($response);

            return $auth0;
        }

        return null;
    }

    /**
     * @return boolean
     */
    public function getIsAdmin()
    {
        return in_array(Yii::$app->auth0user->identity->email, $this->adminEmails);
    }
}
