<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$module = Yii::$app->getModule('auth0');

$auth0 = new \Auth0\SDK\Auth0([
    'domain' => $module->domain,
    'client_id' => $module->client_id,
    'client_secret' => $module->client_secret,
    'redirect_uri' => $module->redirect_uri,
    'scope' => 'openid profile email',
]);

$auth0->login();
?>

