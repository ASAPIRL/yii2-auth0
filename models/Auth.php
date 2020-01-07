<?php
/**
 * @link http://www.euqol.com/
 * @copyright Copyright (c) 2015 Su anli
 * @license http://www.euqol.com/license/
 */

namespace thyseus\auth0\models;

use Yii;

/**
 * This is the model class for table "{{%auth}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $source
 *
 * @author Su anli <anli@euqol.com>
 * @since 1.0.0
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth0_auth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'source'], 'required'],
            [['user_id'], 'integer'],
            [['source'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'source' => 'Source',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Auth0User::className(), ['id' => 'user_id']);
    }
}
