<?php

namespace ahvla\laravel\filter;

use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class RouteLoadCache
{
    /**
     * @var Repository
     */
    private $cache;

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    public function filter(Route $route, Request $request)
    {
        $cacheContentKey = RouteSaveCache::getUniqueKey($route, $request);

        if ($this->cache->has($cacheContentKey)) {
            return $this->cache->get($cacheContentKey);
        }
    }

}