<?php

namespace apple\repository\ar;

use common\exception\DbException;
use apple\entity\Apple;
use apple\exception\AppleNotFoundException;
use apple\repository\AppleRepository as IAppleRepository;
use Ramsey\Uuid\Uuid;

final readonly class AppleRepository implements IAppleRepository
{
    public function nextId(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function get(string $id): Apple
    {
        $p = AppleProjection::findOne($id);
        if ($p === null) {
            throw new AppleNotFoundException("Яблоко с id $id не найдено");
        }

        return $p->toEntity();
    }

    public function add(Apple $apple): void
    {
        $p = AppleProjection::fromEntity($apple);

        try {
            $result = $p->save();
        } catch (\Exception $ex) {
            throw new DbException($ex->getMessage(), $ex->getCode(), $ex);
        }

        if ($result === false) {
            throw new DbException("Не удалось сохранить данные");
        }
    }

    public function batchAdd(array $apples): void
    {
        if (empty($apples)) {
            return;
        }

        $rows = [];
        foreach ($apples as $apple) {
            $rows[] = array_values(AppleProjection::fromEntity($apple)->getAttributes());
        }

        try {
            $result = AppleProjection::getDb()->createCommand()->batchInsert(AppleProjection::tableName(), AppleProjection::getTableSchema()->getColumnNames(), $rows)->execute();
        } catch (\Exception $ex) {
            throw new DbException($ex->getMessage(), $ex->getCode(), $ex);
        }

        if ($result === false) {
            throw new DbException("Не удалось сохранить данные");
        }
    }

    public function update(Apple $apple): void
    {
        $p = AppleProjection::fromEntity($apple);
        try {
            $result = AppleProjection::getDb()->createCommand()->update(
                AppleProjection::tableName(),
                $p->toArray(),
                ['id' => $p->id],
            )->execute();

        } catch (\Throwable $ex) {
            throw new DbException($ex->getMessage(), $ex->getCode(), $ex);
        }

        if ($result === false) {
            $errors = $p->getErrorSummary(true);
            throw new DbException("Не удалось сохранить данные: " . join("\r\n", $errors));
        }
    }

    public function remove(Apple $apple): void
    {
        AppleProjection::deleteAll(['id' => $apple->id()]);
    }
}