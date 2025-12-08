<?php

declare(strict_types=1);

namespace apple\asset;

use frontend\assets\AppAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class AppleAsset extends AssetBundle
{
    public $sourcePath = '@apple/asset/dst';

    public $js = [
      'apple.js',
    ];

    public $depends = [
        JqueryAsset::class,
        AppAsset::class,
    ];
}