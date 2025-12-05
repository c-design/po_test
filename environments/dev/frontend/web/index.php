<?php

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../../vendor/autoload.php';

$dotenv = new Dotenv();
$envPath = __DIR__ . '/../../.env';
if (file_exists($envPath)) {
    (new Dotenv())->usePutenv()->loadEnv($envPath, 'APP_ENV');
}

define('YII_DEBUG', getenv('DEBUG_ENABLED'));
define('YII_ENV', getenv('APP_ENV'));

require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

(new yii\web\Application($config))->run();
