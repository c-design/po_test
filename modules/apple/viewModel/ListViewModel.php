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
        if(null !== $this->pagination) {
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
        return sprintf('%.02f', $apple->health() / 100);
    }

    public function getStateTitle(Apple $apple): string
    {
        return $apple->state()->title();
    }

    public function getAppleActionBtns(Apple $apple): string
    {
        $out = '';
        if($apple->isFallen()){
            $out.= Html::button('<i class="bi bi-fork-knife"></i>', ['class' => 'btn btn-sm btn-success', 'title' => 'съесть']);
        }
        if($apple->isAtTree()){
            $out.= Html::button('<i class="bi bi-sm bi-arrow-down"></i>', ['class' => 'btn btn-sm btn-success', 'title' => 'уронить']);
        }

        return $out;
    }

    public function getCurrentPage(): int
    {
        return $this->listForm->page;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getState(): ?string
    {
        return $this->listForm->state;
    }
}