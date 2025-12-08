<?php

declare(strict_types=1);

use apple\asset\AppleAsset;
use apple\enum\AppleState;
use apple\viewModel\ListViewModel;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Modal as BootstrapModal;

/**
 * @var $viewModel ListViewModel
 */

AppleAsset::register($this);

$pager = LinkPager::widget(['pagination' => $viewModel->getPagination()]);

?>
<div id="listContent">
    <div class="row justify-content-between">
        <div class="col col-4">
            Текущая страница: <?= $viewModel->getCurrentPage() ?> <br/>
            Всего записей: <?= $viewModel->getTotalCount() ?>
        </div>
        <div class="col col-auto">
            <?= Html::a(
                'Сгенерировать',
                ['/apple/apple/generate'],
                ['class' => 'btn btn-primary', 'id' => 'generateApples']
            ) ?>
        </div>
    </div>
    <div class="row justify-content-between mt-3">
        <div class="col col-4">
            <?= $pager ?>
        </div>
        <div class="col col-auto">
            <div class="input-group input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing">Фильтр по состоянию</span>
                <?= Html::dropDownList(
                    'state',
                    $viewModel->getState(),
                    array_merge([null => 'Не выбрано'], AppleState::titles()),
                    ['class' => 'form-select']
                ) ?>
            </div>
        </div>
    </div>
    <table class="table border table-striped align-middle">
        <thead>
        <tr>
            <th scope="col" class="text-center" style="width: 50px">#</th>
            <th scope="col" style="width: 50px"></th>
            <th scope="col" class="w-auto">Целостность</th>
            <th scope="col">Состояние</th>
            <th scope="col">Действия</th>
            <th scope="col" width="310">Доп. информация</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($viewModel->list() as $key => $apple) : ?>
            <tr data-id="<?= $apple->id() ?>">
                <td class="text-center" ><?= $viewModel->calculateListKey($key) ?></td>
                <td class="text-center" ><i class="bi bi-apple" style="<?= $viewModel->getAppleStyleColor($apple); ?>"> </i></td>
                <td class="apple-health-count"><?= $viewModel->getHealth($apple); ?></td>
                <td><?= $viewModel->getStateTitle($apple); ?></td>
                <td><?= $viewModel->getAppleActionButtons($apple); ?></td>
                <td>
                    <?php foreach ($viewModel->getAdditionalInfo($apple) as $title => $dt) : ?>
                        <span class="small"><?= $title?> : <?= $dt ?></span><br/>
                    <?php endforeach;?>
                </td>
            </tr>
        <?php
        endforeach; ?>
        </tbody>
    </table>
    <div class="row">
        <?= $pager ?>
    </div>
</div>
<?php
BootstrapModal::begin([
    'id' => 'eatApplesModal',
    'title' => 'Сколько съесть?',
    'size' => BootstrapModal::SIZE_SMALL,
    'options' => ['class' => 'fade'],
]);
?>
<form id="eatForm">
    <div class="mb-3">
        <label class="col-form-label">от 1 до 100:</label>
        <input type="number" class="form-control" max="100" name="healthCount" required>
        <input type="hidden" name="id" value="">
        <div id="appleNotifications" class="form-text"></div>
    </div>
    <button type="submit" class="btn btn-primary">Съесть</button>
</form>
<?php
BootstrapModal::end();
?>
