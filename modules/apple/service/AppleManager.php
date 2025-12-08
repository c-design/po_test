<?php

declare(strict_types=1);

namespace apple\service;

use apple\entity\Apple;
use apple\factory\AppleFactory;
use common\components\Clock\Clock;
use apple\repository\AppleRepository;

final readonly class AppleManager
{
    public function __construct(
        private AppleRepository $repository,
        private AppleFactory $factory,
        private AppleCacheService $appleCacheService,
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
        $this->appleCacheService->invalidateList();

        return $apples;
    }

    public function fallToGroundById(string $id): void
    {
        $apple = $this->repository->get($id);
        $apple->fallToGround($this->clock->now());

        $this->repository->update($apple);
        $this->appleCacheService->invalidateList();
    }

    public function eatById(string $id, int $health): Apple
    {
        $apple = $this->repository->get($id);
        $apple->eat($health, $this->clock->now());

        $this->repository->update($apple);
        $this->appleCacheService->invalidateList();

        return $apple;
    }
}