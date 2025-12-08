<?php

declare(strict_types=1);

namespace apple\query\cached;

use apple\enum\AppleCacheTag;
use apple\query\dto\ListFilterData;
use apple\query\FindAllByFilter as IFindAllByFilter;
use yii\caching\Cache;
use yii\caching\TagDependency;

final readonly class FindAllByFilter implements IFindAllByFilter
{
    private const int DURATION = 3600;

    public function __construct(
        private Cache $cache,
        private IFindAllByFilter $query,
    ) {
    }

    public function fetch(ListFilterData $data): \Generator
    {
        $cacheKey = $this->makeCacheKey($data);
        if(!$this->cache->exists($cacheKey)) {
            $result = iterator_to_array($this->query->fetch($data));
            $this->cache->set($cacheKey, $result, self::DURATION, new TagDependency(['tags' => AppleCacheTag::LIST->value]));
        } else {
            $result = $this->cache->get($cacheKey);
        }

        if(!$result) {
            $this->cache->delete($cacheKey);
            $result = iterator_to_array($this->query->fetch($data));
        }

        foreach ($result as $item) {
            yield $item;
        }
    }

    public function total(ListFilterData $data): int {
        $cacheKey = $this->makeCacheKey($data). '.total';
        if(!$this->cache->exists($cacheKey)) {
            $result = $this->query->total($data);
            $this->cache->set($cacheKey, $result, self::DURATION, new TagDependency(['tags' => AppleCacheTag::LIST->value]));
        } else {
            $result = $this->cache->get($cacheKey);
        }

        if(!$result) {
            $this->cache->delete($cacheKey);
            $result = $this->query->total($data);
        }

        return $result;
    }

    private function makeCacheKey(ListFilterData $data): string
    {
        $serialized = serialize($data);
        return sprintf("%s.%s", AppleCacheTag::LIST->value, md5($serialized));
    }
}