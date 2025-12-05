<?php

declare(strict_types=1);

use apple\asset\AppleAsset;
use apple\enum\AppleState;
use apple\viewModel\ListViewModel;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;

/**
 * @var $viewModel ListViewModel
 */

AppleAsset::register($this);

$pager = LinkPager::widget(['pagination' => $viewModel->getPagination()]);


?>

<div class="row justify-content-between" id="listContent">
    <div class="col col-4">
        Текущая страница: <?= $viewModel->getCurrentPage() ?> <br/>
        Всего записей: <?= $viewModel->getTotalCount() ?>
    </div>
    <div class="col col-auto">
        <?= Html::a('Сгенерировать', ['/apple/apple/generate'], ['class' => 'btn btn-primary', 'id' => 'generateApples']) ?>
    </div>
</div>
<div class="row justify-content-between mt-3">
    <div class="col col-4">
        <?= $pager ?>
    </div>
    <div class="col col-auto">
        <div class="input-group input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing">Фильтр по состоянию</span>
            <?= Html::dropDownList('state', $viewModel->getState(), AppleState::titles(), ['class' => 'form-select']) ?>
        </div>
    </div>
</div>
<table class="table border table-striped">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col"></th>
        <th scope="col">Целостность</th>
        <th scope="col">Состояние</th>
        <th scope="col">Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($viewModel->list() as $key => $apple) : ?>
        <tr>
            <td><?= ++$key ?></td>
            <td><i class="bi bi-apple" style="<?= $viewModel->getAppleStyleColor($apple); ?>"> </i></td>
            <td><?= $viewModel->getHealth($apple); ?></td>
            <td><?= $viewModel->getStateTitle($apple); ?></td>
            <td><?= $viewModel->getAppleActionBtns($apple); ?></td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>
<div class="row">
    <?= $pager ?>
</div>