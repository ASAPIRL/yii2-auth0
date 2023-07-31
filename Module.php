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
     * @var string
     */
    public $redirect_uri = '';

    /**
     * @var string the callback url that auth0 redirect the user after he logs out.
     *             ensure to add this url to your auth0 account at https://manage.auth0.com.
     * @link https://auth0.com/docs/quickstart/webapp/php/#logout
     */
    public $redirect_uri_logout = ['//user/logout'];

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
            'domain'             => $this->domain,
            'clientId'           => $this->client_id,
            'clientSecret'       => $this->client_secret,
            'redirectUri'        => $this->redirect_uri,
            'redirectUriLogout'  => $this->redirect_uri_logout, //todo
            'persistIdToken'     => $this->persist_id_token,
            'persistAccessToken' => $this->persist_access_token,
            'tokenAlgorithm'     => 'RS256'
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
