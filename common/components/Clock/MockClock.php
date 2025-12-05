<?php

declare(strict_types=1);

namespace common\components\Clock;

final readonly class MockClock implements Clock
{
    public function __construct(
        private \DateTimeImmutable $now,
    ) {
    }

    #[\Override]
    public function now(): \DateTimeImmutable
    {
        return $this->now;
    }

    public function random(): \DateTimeImmutable
    {
        return $this->now;
    }
}
