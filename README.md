thyseus\auth0
=============
Yii2 Auth0

Credits
-------
This is a modernized fork of the abondoned anli/yii2-auth0 project. The old one uses a very 
old version of auth0, so i decided to make a hard fork and modernize it.

It does _not_ use auth0-lock anymore but plain php registration.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

    php composer.phar require --prefer-dist thyseus/yii2-auth0 "*"

or add

    "thyseus/yii2-auth0": "*"

to the require section of your `composer.json` file.

Configuration
-------------

Ensure to have `Yii::$app->user` configured in your application.
 
You will also need to have an `app\models\User.php` with at least this attributes:

username
email
password
source
created_at
updated_at

configured properly for this extension to work. yii2-auth0 will place the string 'auth0' into
the 'source' attribute to mark this user as auth0 user.

Update the `modules` section with:

```php
    [
     'auth0' => require __DIR__ . '/auth0.php',
    ],
```
    
Add a config/auth0.php. You could handle your development keys here:

```php
<?php
$config = [
        'class' => 'thyseus\auth0\Module',
        'adminEmails' => ['admin@example.com'],
    ];

$filenameLocal = __DIR__ . '/auth0_local.php';

if (file_exists($filenameLocal)) {
    return array_merge($config, require $filenameLocal);
}

return $config;
```

For the productive keys, you can create a new file in `config/auth0_local.php`:

```php
<?php
    return [
        'serviceId' => '',
        'domain' => '', // just domain, without protocol (without https://)
        'client_id' => '',
        'client_secret' => '',
        'redirect_uri' => '',
        'api_tokens' => [
            'users_read' => '',
            'users_update' => '',
        ]
    ];
```

And add it to your to your `.gitignore` file, so live keys are not pushed into your repository:

    /config/auth0_local.php

Login to auth0 (https://manage.auth0.com/dashboard) and update the `Allowed Callback Urls` in 
your setting page.

Usage
-----

Update your `url` section for your login button to `[/auth0/user/login]`.

Update your `url` section for your logout button to `[/auth0/user/logout]`.

To show the login user, use:

    Html::encode(Yii::$app->user->identity->username);

FAQs
----

If you encounter the following error

    \JWT not found

Change the `firebase/php-jwt` version to `v2.2.0`:

    cd @vendor/firebase/php-jwt
    git checkout v2.2.0

Update the `@vendor/composer/autoload_classmap.php` with:

    'BeforeValidException' => $vendorDir . '/firebase/php-jwt/Exceptions/BeforeValidException.php',
    'JWT' => $vendorDir . '/firebase/php-jwt/Authentication/JWT.php',

If you encounter the following error:

    Cannot handle token prior to 2015-08-05T10:42:34+0200

And your system time forward a few minutes.

If you encounter the following error:

    cURL error 60: SSL certificate problem: self signed certificate in certificate chain

Download [CA](http://curl.haxx.se/ca/cacert.pem) to;

    C:\xampp\php\ca\cacert.pem

and update C:\xampp\php\php.ini with

    curl.cainfo=C:\xampp\php\ca\cacert.pem

Restart your apache2 server.
