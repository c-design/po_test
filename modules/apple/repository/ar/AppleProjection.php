<?php

declare(strict_types=1);

namespace apple\repository\ar;

use DateTimeImmutable;
use apple\entity\Apple;
use apple\enum\AppleState;
use yii\db\ActiveRecord;

/**
 * @property string $id
 * @property string $color
 * @property int $health
 * @property string $state
 * @property string $created_at
 * @property null|string $fell_at
 * @property null|string $dead_at
 */
class AppleProjection extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'apples';
    }

    public static function fromEntity(Apple $e): self
    {
        $p = new self();
        $p->id = $e->id();
        $p->color = $e->color();
        $p->health = $e->health();
        $p->state = $e->state();
        $p->created_at = $e->createdAt()->format('Y-m-d H:i:s');
        $p->fell_at = $e->fellAt()?->format('Y-m-d H:i:s');
        $p->dead_at = $e->deadAt()?->format('Y-m-d H:i:s');

        return $p;
    }

    public function toEntity(): Apple
    {
        $e = new Apple(
            $this->id,
            $this->color,
            new DateTimeImmutable($this->created_at),
        );

        switch ($this->state) {
            case AppleState::DEAD->value:
                $e->dead(new DateTimeImmutable($this->dead_at));
                break;
            case AppleState::AT_GROUND->value:
                $e->fallToGround(new DateTimeImmutable($this->fell_at));
                break;
            case AppleState::AT_TREE->value:
                break;
            default:
                throw new \RuntimeException('Неизвестное состояние яблока');
        }

        $e->setHealth($this->health);
        $e->setColor($this->color);

        return $e;
    }
}