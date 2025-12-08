<?php

namespace frontend\assets\alertify;

use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapIconAsset;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class Alertify extends AssetBundle
{
    public $sourcePath = __DIR__;

    public $css = [
        'css/alertify.css',
        'css/themes/bootstrap.css',
    ];

    public $js = [
        'js/alertify.min.js',
        'js/init.js',
    ];

    public $depends = [
        BootstrapAsset::class,
        BootstrapIconAsset::class,
    ];
}
