<?php
if (('localhost' != $_SERVER['HTTP_HOST']) && ('127.0.0.1' != $_SERVER['HTTP_HOST'])) {
    // set production mode of application
    defined('YII_ENV') or define('YII_ENV', 'prod');
    defined('YII_DEBUG') or define('YII_DEBUG', false);
}
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../config/web.php';
(new yii\web\Application($config))->run();
