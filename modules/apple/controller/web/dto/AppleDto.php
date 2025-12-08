<?php

namespace apple\controller\web\dto;

use apple\entity\Apple;

final readonly class AppleDto
{
    public function __construct(
        public string $id,
        public string $color,
        public string $health,
        public string $state,
        public string $createAt,
        public ?string $fellAt,
        public ?string $deadAt,
    ) {
    }

    public static function fromEntity(Apple $apple): self
    {
        return new self(
            $apple->id(),
            $apple->color(),
            $apple->healthAsDecimalString(),
            $apple->state()->value,
            $apple->createdAt()->format(DATE_ATOM),
            $apple->fellAt()?->format(DATE_ATOM),
            $apple->deadAt()?->format(DATE_ATOM),
        );
    }
}