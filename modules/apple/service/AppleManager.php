<?php

declare(strict_types=1);

namespace apple\service;

use apple\factory\AppleFactory;
use common\components\Clock\Clock;
use apple\entity\Apple;
use apple\repository\AppleRepository;

final readonly class AppleManager
{
    public function __construct(
        private AppleRepository $repository,
        private AppleFactory $factory,
        private Clock $clock,
    ) {
    }

    public function randomSeed(int $count): array
    {
        $apples = [];
        for ($i = 0; $i < $count; $i++) {
            $apples[] = $this->factory->createRandom();
        }

        $this->repository->batchAdd($apples);

        return $apples;
    }

    public function fallToGroundById(string $id): void
    {
        $apple = $this->repository->get($id);
        $apple->fallToGround($this->clock->now());

        $this->repository->update($apple);
    }

    public function eatById(string $id, int $health): void
    {
        $apple = $this->repository->get($id);
        $apple->eat($health);

        $this->repository->update($apple);
    }
}