<?php
/**
 * @link http://www.euqol.com/
 * @copyright Copyright (c) 2015 Su anli
 * @license http://www.euqol.com/license/
 */

namespace thyseus\auth0\assets;

use yii\web\AssetBundle;

/**
 * This is the asset class for the Auth0Lock.
 * @author Su anli <anli@euqol.com>
 * @since 1.0.0
 */
class Auth0LockAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $js = [
        'https://cdn.auth0.com/js/lock/11.20.2/lock.min.js',
    ];
}
