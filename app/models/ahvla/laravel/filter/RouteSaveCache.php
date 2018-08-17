<?php

namespace ahvla\laravel\filter;

use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use ahvla\authentication\AuthenticationManager;

class RouteSaveCache
{
    /**
     * @var Repository
     */
    private $cache;

    public function __construct(AuthenticationManager $authenticationManager, Repository $cache)
    {
        $this->authenticationManager = $authenticationManager;
        $this->cache = $cache;
    }

    public function filter(Route $route, Request $request, $response)
    {
        if (!$this->authenticationManager->isUserLoggedIn()) {
            return;
        }

        $cacheContentKey = self::getUniqueKey($route, $request);

        if (!$this->cache->has($cacheContentKey)) {
            $this->cache->put($cacheContentKey, $response, 60);
        }
    }

    /**
     * @param Route $route
     * @param Request $request
     * @return string
     */
    public static function getUniqueKey(Route $route, Request $request)
    {
        $httpMethods = implode('-', $route->getMethods());
        $parameters = str_replace('', '-', implode('-', array_values($request->all())));
        $urlEnding = substr(strrchr($route->getUri(), '/'), 1);

        return $httpMethods . '-' . $urlEnding . '-' . $parameters;
    }
}