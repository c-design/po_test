<?php

declare(strict_types=1);

namespace apple\query\dto;

use apple\enum\AppleState;
use apple\query\FindAllByFilter;

final class ListFilterData
{
    public function __construct(
        public int $limit = FindAllByFilter::DEFAULT_LIMIT,
        public int $page = 1,
        public ?AppleState $state = null,
    ) {
    }
}