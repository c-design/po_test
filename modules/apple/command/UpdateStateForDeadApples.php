<?php

declare(strict_types=1);

namespace apple\command;

interface UpdateStateForDeadApples
{
    public function exec(): void;
}