<?php

declare(strict_types=1);

namespace apple\entity;

use apple\enum\AppleState;
use apple\exception\WrongAppleStateException;
use DateTimeInterface;

final class Apple
{
    public const int MAX_LIFETIME = 60 * 60 * 5;

    private int $health = 100;
    private AppleState $state;
    private ?\DateTimeInterface $fellAt = null;
    private ?\DateTimeInterface $deadAt = null;
    private ?\DateTimeInterface $eatenAt = null;

    public function __construct(
        private readonly string $id,
        private string $color,
        private \DateTimeInterface $createdAt,
    ) {
        $this->state = AppleState::AT_TREE;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function state(): AppleState
    {
        return $this->state;
    }

    public function isAtTree(): bool
    {
        return $this->state === AppleState::AT_TREE;
    }

    public function isFallen(): bool
    {
        return $this->state === AppleState::AT_GROUND;
    }

    public function fallToGround(\DateTimeInterface $currentTime): self
    {
        $this->setState(AppleState::AT_GROUND);
        $this->fellAt = $currentTime;

        return $this;
    }

    public function eat(int $duration, \DateTimeInterface $currentTime): self {
        if(AppleState::AT_GROUND !== $this->state) {
            throw new WrongAppleStateException("Нельзя есть яблоко, если оно сгнило или ещё на дереве");
        }

        $healthAfterEat =  $this->health - $duration;
        if($healthAfterEat < 0){
            throw new \InvalidArgumentException(sprintf("Нельзя съесть больше %d", $this->health));
        }

        $this->health -= $duration;
        $this->eatenAt = $currentTime;

        if($healthAfterEat == 0){
            $this->dead($currentTime);
        }

        return $this;
    }

    public function eatenAt(): ?DateTimeInterface
    {
        return $this->eatenAt;
    }

    public function setEatenAt(\DateTimeImmutable $val): self
    {
        $this->eatenAt = $val;
        return $this;
    }

    public function isDead(): bool
    {
        return $this->state === AppleState::DEAD;
    }

    public function dead(\DateTimeInterface $currentTime): self
    {
        $this->setState(AppleState::DEAD);
        $this->deadAt = $currentTime;

        return $this;
    }

    public function color(): string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function health(): int
    {
        return $this->health;
    }

    public function setHealth(int $val): self
    {
        if($val > 100 || $val < 0) {
            throw new \OutOfBoundsException("Величина целостности яблока не может выходить за предел от 0 до 100");
        }

        $this->health = $val;
        return $this;
    }

    public function createdAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function deadAt(): ?\DateTimeInterface
    {
        return $this->deadAt;
    }

    public function fellAt(): ?\DateTimeInterface
    {
        return $this->fellAt;
    }

    private function setState(AppleState $newState): void
    {
        if ($newState === $this->state) {
            return;
        }

        if (!$this->state->canBeChangedTo($newState)) {
            throw new WrongAppleStateException(
                sprintf('Нельзя менять состояние из "%s" в "%s"', $this->state->title(), $newState->title())
            );
        }

        $this->state = $newState;
    }

    public function healthAsDecimalString(): string
    {
        return sprintf('%.02f', $this->health() / 100);
    }
}