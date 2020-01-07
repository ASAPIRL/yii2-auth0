<?php

namespace thyseus\auth0\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

use app\models\User;

/**
 * User model
 *
 * @property integer $id
 * @property string  $username
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $email
 * @property string  $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $password write-only password
 */
class Auth0User extends User implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (! static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds user by auth0 authenticated user
     *
     * @return mixed Return null if no matching record
     */
    public static function findByAuth0($auth0Data)
    {
        $query = self::find()->where(['source' => 'auth0']);

        if (! $query->exists()) {
            return self::createFromAuth0($auth0Data);
        }

        return $query->one() ?? null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuth()
    {
        return $this->hasOne(Auth::className(), ['user_id' => 'id']);
    }

    /**
     * @return $mixed Return false on error
     */
    public static function createFromAuth0(array $auth0Data)
    {
        // check if email is taken

        $model = User::find()
            ->where([
                'email'  => $auth0Data['email'],
                'source' => 'auth0',
            ])->one();

        if ($model) {
            return $model;
        }

        $model = new User([
            'username'   => $auth0Data['nickname'],
            'email'      => $auth0Data['email'],
            'password'   => Yii::$app->security->generateRandomString(6),
            'source'     => 'auth0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $model->save(false,
            ['username', 'email', 'password', 'source', 'created_at', 'updated_at']);

        return $model;
    }

    /**
     * Generate an array for a select2 control.
     *
     * @return array
     */
    public static function select2Data()
    {
        $query = self::find()
            ->joinWith('tenantUsers')
            ->andWhere(['{{%tenant_user}}.tenant_id' => Yii::$app->tenant->identity->id])
            ->select(['{{%user}}.id', 'username'])
            ->orderBy('username');

        $array = $query
            ->asArray()->all();

        return ArrayHelper::map($array, 'id', 'username');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTenantUsers()
    {
        return $this->hasMany(TenantUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return mixed
     */
    public static function ByTenant()
    {
        return self::find()
            ->joinWith('tenantUsers')
            ->andWhere(['{{%tenant_user}}.tenant_id' => Yii::$app->tenant->identity->id]);
    }

    /**
     * @inheritdoc
     * @return TimesheetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
