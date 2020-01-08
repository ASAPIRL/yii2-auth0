<?php
/**
 * @link      http://www.euqol.com/
 * @copyright Copyright (c) 2015 Su anli
 * @license   http://www.euqol.com/license/
 */

namespace thyseus\auth0\models;

use Yii;

/**
 * This is the model class for [[\Auth0\SDK\Auth0]].
 *
 * @author Su anli <anli@euqol.com>
 * @since  1.0.0
 */
class Auth0 extends \Auth0\SDK\Auth0
{
    /**
     * @param string $tenantName
     *
     * @return boolean Return true if the login is validated
     * @throws \yii\web\HttpException
     */
    public function validateTenant($tenantName)
    {
        if (! in_array($tenantName, $this->getTenants())) {
            $this->logout();
            throw new \yii\web\HttpException(400, 'Not authorized to use this tenant: '
                . $tenantName
                . ' not found in '
                . var_dump($this->getTenants())
                , 405);
        }

        return true;
    }

    /**
     * @return string
     */
    public function getDefaultTenant()
    {
        return $this->getTenants()[0];
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getServiceIds()
    {
        return array_keys($this->getPermissions());
    }

    /**
     * @return array
     */
    public function getTenants()
    {
        if (isset($this->getPermissions()[$this->getServiceId()])) {
            return array_keys($this->getPermissions()[$this->getServiceId()]);
        }

        return [];
    }

    /**
     * @return string
     */
    public static function getServiceId()
    {
        return Yii::$app->getModule('auth0')->service_id;
    }
}
