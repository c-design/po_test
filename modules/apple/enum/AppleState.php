<?php

declare(strict_types=1);

namespace apple\enum;

enum AppleState: string
{
    case AT_TREE = 'at_tree';
    case AT_GROUND = 'at_ground';
    case DEAD = 'dead';

    public static function list(): array
    {
        return [
            AppleState::AT_TREE,
            AppleState::AT_GROUND,
            AppleState::DEAD,
        ];
    }

    public static function values(): array
    {
        return [
            AppleState::AT_TREE->value,
            AppleState::AT_GROUND->value,
            AppleState::DEAD->value,
        ];
    }

    public static function titles(): array
    {
        return [
            AppleState::AT_TREE->value => 'На дереве',
            AppleState::AT_GROUND->value => 'На земле',
            AppleState::DEAD->value => 'Сгнило',
        ];
    }

    public function title(): string
    {
        return match ($this) {
            AppleState::AT_TREE => 'На дереве',
            AppleState::AT_GROUND => 'На земле',
            AppleState::DEAD => 'Сгнило',
        };
    }

    public function canBeChangedTo(AppleState $newState): bool
    {
        return match ($newState) {
            AppleState::AT_TREE => false,
            AppleState::AT_GROUND => $this === AppleState::AT_TREE,
            AppleState::DEAD => in_array($this, [AppleState::AT_GROUND, AppleState::AT_TREE], true),
        };
    }
}