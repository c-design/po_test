<?php

declare(strict_types=1);

namespace apple\forms;

use apple\repository\ar\AppleProjection;
use yii\base\Model;
use yii\validators\ExistValidator;
use yii\validators\FilterValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

class EatForm extends Model
{
    public mixed $id;
    public mixed $healthCount;

    public function rules(): array
    {
        return [
            [
                ['id', 'healthCount'],
                RequiredValidator::class
            ],
            [
                'id',
                StringValidator::class,
                'min' => 36,
                'max' => 36,
            ],
            [
                'id',
                ExistValidator::class,
                AppleProjection::class,
            ],
            [
                'healthCount',
                NumberValidator::class,
                'integerOnly' => true,
                'min' => 1,
                'max' => 100,
            ],
            [
                'healthCount',
                FilterValidator::class,
                'filter' => fn(mixed $value) => $value ? (int)$value : null,
            ]
        ];
    }
}