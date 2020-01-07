<?php
/**
 * @link http://www.euqol.com/
 * @copyright Copyright (c) 2015 Su thyseus
 * @license http://www.euqol.com/license/
 */

namespace thyseus\auth0\components;

use thyseus\auth0\models\Tenant as TenantModel;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * This is the component class for the Tenant.
 * @author Su anli <anli@euqol.com>
 * @since 1.1.0
 */
class Tenant extends Component
{
    /**
     * @return boolean
     */
    public static function login($model)
    {
        return Yii::$app->session->set('user.tenantId', $model->id);
    }

    /**
     * @return mixed
     */
    public static function getIdentity()
    {
        return TenantModel::findOne(Yii::$app->session->get('user.tenantId'));
    }
}
