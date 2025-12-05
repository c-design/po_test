<?php

declare(strict_types=1);

namespace common\components\Clock;

final readonly class SystemClock implements Clock
{
    public function __construct(
        private ?\DateTimeZone $timezone = null,
    ) {
    }

    #[\Override]
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', $this->timezone);
    }

    #[\Override]
    public function random(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable('now', $this->timezone))
            ->sub(new \DateInterval(sprintf('P%dD', random_int(1, 7))));
    }
}
