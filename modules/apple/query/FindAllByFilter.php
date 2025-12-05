<?php

declare(strict_types=1);

namespace apple\query;

use apple\query\dto\ListFilterData;

interface FindAllByFilter
{
    public const int DEFAULT_LIMIT = 25;

    public function fetch(ListFilterData $data): \Generator;
    public function total(ListFilterData $data): int;
}