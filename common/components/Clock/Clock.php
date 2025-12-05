<?php

declare(strict_types=1);

namespace common\components\Clock;

/**
 * Текущее системное время.
 * Предназначено только для получения объекта текущего времени.
 * Не расширять другими методами!
 */
interface Clock
{
    /**
     * Получение текущих даты и времени.
     */
    public function now(): \DateTimeImmutable;

    public function random(): \DateTimeImmutable;
}
