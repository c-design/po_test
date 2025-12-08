<?php

declare(strict_types=1);

namespace apple\enum;

enum AppleCacheTag: string
{
    case LIST = 'apple.list';
    case ITEM = 'apple.item';
}