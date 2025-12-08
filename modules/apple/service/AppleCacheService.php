<?php

declare(strict_types=1);

namespace apple\service;

use apple\enum\AppleCacheTag;
use yii\caching\Cache;
use yii\caching\TagDependency;

final readonly class AppleCacheService
{
    public function __construct(
        private Cache $cache
    ){
    }

    public function invalidateList(): void
    {
        TagDependency::invalidate($this->cache, [AppleCacheTag::LIST->value]);
    }
}