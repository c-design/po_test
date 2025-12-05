<?php

namespace apple\controller\console;

use apple\service\AppleManager;
use yii\console\Controller;
use yii\console\ExitCode;

final class SeedController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly AppleManager $appleManager,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * Рандомное заполнение коллекции яблок
     *
     * For example,
     *
     * ```
     * yii apple/seed/random --count=50
     * ```
     */
    public function actionRandom(int $count = 100): int
    {
        $this->appleManager->randomSeed($count);
        return ExitCode::OK;
    }
}