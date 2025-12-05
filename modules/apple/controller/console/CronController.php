<?php

declare(strict_types=1);

namespace apple\controller\console;

use apple\command\UpdateStateForDeadApples;
use yii\console\ExitCode;
use yii\console\Controller;

final class CronController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly UpdateStateForDeadApples $updateStateForDeadApples,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * Перерасчёт состояния яблок
     *
     * For example,
     *
     * ```
     * yii apple/cron/recalculate-state
     * ```
     */
    public function actionRecalculateState(): int
    {
        $this->updateStateForDeadApples->exec();
        return ExitCode::OK;
    }
}