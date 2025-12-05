<?php

declare(strict_types=1);

namespace apple\forms;

use apple\enum\AppleState;
use apple\query\dto\ListFilterData;
use apple\query\FindAllByFilter;
use yii\validators\FilterValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;

class ListForm extends \yii\base\Model
{
    public mixed $limit = FindAllByFilter::DEFAULT_LIMIT;

    public mixed $page = 1;

    public mixed $state = null;

    public function rules(): array
    {
        return [
            [
                ['limit', 'page'],
                NumberValidator::class,
                'integerOnly' => true,
                'min' => 1
            ],
            [
                ['limit'],
                NumberValidator::class,
                'integerOnly' => true,
                'min' => FindAllByFilter::DEFAULT_LIMIT,
                'max' => 100
            ],
            [
                ['limit'],
                NumberValidator::class,
                'integerOnly' => true,
                'max' => PHP_INT_MAX
            ],
            [
                ['state'],
                RangeValidator::class,
                'range' => \apple\enum\AppleState::values(),
                'strict' => true,
            ],
            [
                ['page', 'limit'],
                FilterValidator::class,
                'filter' => fn(mixed $val) => $val ? (int)$val : null,
            ]
        ];
    }

    public function getFilterDto(): ListFilterData
    {
        return new ListFilterData(
            (int)$this->limit,
            (int)$this->page,
            $this->state ? AppleState::from($this->state) : null,
        );
    }
}