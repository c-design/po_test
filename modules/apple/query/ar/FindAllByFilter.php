<?php

declare(strict_types=1);

namespace apple\query\ar;

use apple\query\dto\ListFilterData;
use apple\query\FindAllByFilter as IFindAllByFilter;
use apple\repository\ar\AppleProjection;
use yii\db\ActiveQuery;

final readonly class FindAllByFilter implements IFindAllByFilter
{
    public function fetch(ListFilterData $data): \Generator
    {
        $list = $this->buildBaseQuery($data)
            ->limit($data->limit)
            ->offset($data->page > 1 ? ($data->page - 1) * $data->limit : 0)
            ->all();

        foreach ($list as $item) {
            yield $item->toEntity();
        }
    }

    public function total(ListFilterData $data): int
    {
        return $this->buildBaseQuery($data)->count();
    }

    private function buildBaseQuery(ListFilterData $data): ActiveQuery
    {
        $q = AppleProjection::find();

        if(null !== $data->state){
            $q->where(['state' => $data->state->value]);
        }

        return $q;
    }
}