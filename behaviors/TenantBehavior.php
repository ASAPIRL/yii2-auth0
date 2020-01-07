<?php
/**
* @link http://www.euqol.com/
* @copyright Copyright (c) 2015 Su anli
* @license http://www.euqol.com/license/
*/

namespace thyseus\auth0\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the tenant behavior class.
 * @author Su anli <anli@euqol.com>
 * @since 1.2.0
 */
class TenantBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'updateTenantId',
        ];
    }

    /**
     * Update the tenant id
     */
    public function updateTenantId()
    {
        $this->owner->tenant_id = Yii::$app->tenant->identity->id;
    }
}
