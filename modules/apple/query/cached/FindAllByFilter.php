<?php

declare(strict_types=1);

namespace apple\query\cached;

use apple\query\dto\ListFilterData;
use apple\query\FindAllByFilter as IFindAllByFilter;
use yii\caching\Cache;
use yii\caching\TagDependency;

final readonly class FindAllByFilter implements IFindAllByFilter
{
    public const string CACHE_TAG = 'apple.list';

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
            $this->cache->set($cacheKey, $result, self::DURATION, new TagDependency(['tags' => self::CACHE_TAG]));
        } else {
            $result = $this->cache->get($cacheKey);
        }

        if(!$result) {
            $this->cache->delete($cacheKey);
            return null;
        }

        foreach ($result as $item) {
            yield $item;
        }
    }

    public function total(ListFilterData $data): int {
        $cacheKey = $this->makeCacheKey($data). '.total';
        if(!$this->cache->exists($cacheKey)) {
            $result = $this->query->total($data);
            $this->cache->set($cacheKey, $result, self::DURATION, new TagDependency(['tags' => self::CACHE_TAG]));
        } else {
            $result = $this->cache->get($cacheKey);
        }

        if(false === $result) {
            $this->cache->delete($cacheKey);
            return 0;
        }

        return $result;
    }

    private function makeCacheKey(ListFilterData $data): string
    {
        $serialized = serialize($data);
        return sprintf("%s.%s", self::CACHE_TAG, md5($serialized));
    }
}