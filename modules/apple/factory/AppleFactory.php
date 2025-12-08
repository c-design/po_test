<?php

declare(strict_types=1);

namespace apple\factory;

use common\components\Clock\Clock;
use DateInterval;
use apple\entity\Apple;
use apple\enum\AppleState;
use apple\helper\ColorGenerator;
use apple\repository\AppleRepository;

final readonly class AppleFactory
{
    public function __construct(
        private AppleRepository $repository,
        private Clock $clock,
    ) {
    }

    public function create(string $color): Apple
    {
        return new Apple(
            $this->repository->nextId(),
            $color,
            $this->clock->now(),
        );
    }

    public function createRandom(): Apple
    {
        $dt = $this->clock->random();
        $states = AppleState::list();
        $state = $states[array_rand($states)];

        $e = new Apple(
            $this->repository->nextId(),
            ColorGenerator::randomHex(),
            $dt,
        );

        switch ($state) {
            case AppleState::AT_GROUND:
                $e->fallToGround($dt->add(new DateInterval(\sprintf('PT%dH', \rand(1, 6)))));
                $e->eat(\rand(1, 99), $e->fellAt()->add(new DateInterval(\sprintf('PT%dS', \rand(10, 60)))));
                break;
            case AppleState::DEAD:
                $e->fallToGround($dt->add(new DateInterval(\sprintf('PT%dH', \rand(1, 6)))));

                if(\random_int(0, 1) === 1) {
                    $e->eat(rand(1, 99), $e->fellAt()->add(new DateInterval(\sprintf('PT%dS', \rand(10, 60)))));
                }

                $e->dead($e->fellAt()->add(new DateInterval(\sprintf('PT%dS', Apple::MAX_LIFETIME))));
        }

        return $e;
    }


}