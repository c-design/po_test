<?php

declare(strict_types=1);

namespace apple\viewModel;

use apple\entity\Apple;
use apple\enum\AppleState;
use apple\forms\ListForm;
use apple\query\FindAllByFilter;
use Generator;
use yii\bootstrap5\Html;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

final class ListViewModel
{
    private ?Pagination $pagination = null;

    public function __construct(
        private readonly ListForm $listForm,
        private readonly Generator $apples,
        private readonly int $totalCount,
    ) {
    }

    public function getListForm(): ListForm
    {
        return $this->listForm;
    }

    /**
     * @return Generator<Apple>
     */
    public function list(): Generator
    {
        return $this->apples;
    }

    public function getPagination(): Pagination
    {
        if (null !== $this->pagination) {
            return $this->pagination;
        }

        return $this->pagination = new Pagination([
            'totalCount' => $this->totalCount,
            'pageSizeParam' => 'limit',
            'route' => '/apple/apple/list',
            'defaultPageSize' => FindAllByFilter::DEFAULT_LIMIT,
        ]);
    }

    public function getAppleStyleColor(Apple $apple): string
    {
        return sprintf('color: #%s;', $apple->color());
    }

    public function getHealth(Apple $apple): string
    {
        return $apple->healthAsDecimalString();
    }

    public function getStateTitle(Apple $apple): string
    {
        return $apple->state()->title();
    }

    public function getAppleActionButtons(Apple $apple): string
    {
        $out = '';
        if ($apple->isFallen()) {
            $out .= Html::button(
                '<i class="bi bi-fork-knife"></i>',
                [
                    'class' => 'btn btn-sm btn-success eat-it',
                    'data-id' => $apple->id(),
                    'title' => 'съесть',
                    'data-bs-toggle' => "modal",
                    'data-bs-target' => "#eatApplesModal",
                ]
            );
        }

        if ($apple->isAtTree()) {
            $out .= Html::button(
                '<i class="bi bi-sm bi-arrow-down"></i>',
                [
                    'class' => 'btn btn-sm btn-success drop-it',
                    'data-id' => $apple->id(),
                    'title' => 'уронить'
                ]
            );
        }

        return $out;
    }

    public function getCurrentPage(): int
    {
        return $this->listForm->page;
    }

    public function calculateListKey(int $key): int
    {
        if($this->listForm->page === 1){
            return ++$key;
        }

        return (($this->listForm->page - 1) * $this->listForm->limit) + ++$key;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getState(): ?string
    {
        return $this->listForm->state;
    }

    public function getAdditionalInfo(Apple $apple) : array
    {
        return array_diff([
            'упало на землю' => $apple->fellAt()?->format('d.m.Y H:i:s'),
            'последний укус' => $apple->eatenAt()?->format('d.m.Y H:i:s'),
            'сгнило' => $apple->deadAt()?->format('d.m.Y H:i:s'),
        ], [null]);
    }
}