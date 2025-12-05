<?php

declare(strict_types=1);

namespace apple\command\mysql;

use apple\command\UpdateStateForDeadApples as IUpdateStateForDeadApples;
use common\components\Clock\Clock;
use DateInterval;
use apple\entity\Apple;
use apple\enum\AppleState;
use yii\db\Connection;

final readonly class UpdateStateForDeadApples implements IUpdateStateForDeadApples
{
    public function __construct(
        private Connection $db,
        private Clock $clock,
    ) {
    }

    public function exec(): void
    {
        $sql = <<<SQL
        UPDATE apples SET state = :deadState, dead_at = now() WHERE state = :atGroundState AND fell_at <= :fellAt;
SQL;

        $fellAt = $this->clock
            ->now()
            ->sub(new DateInterval(sprintf('PT%dS', Apple::MAX_LIFETIME)))
            ->format('Y-m-d H:i:s');

        $this->db->createCommand($sql, [
            'deadState' => AppleState::DEAD,
            'atGroundState' => AppleState::AT_GROUND,
            'fellAt' => $fellAt,
        ])->execute();
    }
}