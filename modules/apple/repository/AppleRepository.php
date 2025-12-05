<?php

namespace apple\repository;

use apple\entity\Apple;

interface AppleRepository
{
    public function nextId(): string;

    public function get(string $id): Apple;

    public function add(Apple $apple): void;

    /**
     * @param Apple[] $apples
     */
    public function batchAdd(array $apples): void;

    public function update(Apple $apple): void;

    public function remove(Apple $apple): void;
}