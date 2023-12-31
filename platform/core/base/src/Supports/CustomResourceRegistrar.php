<?php

namespace Cmat\Base\Supports;

use BaseHelper;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Routing\Route;

class CustomResourceRegistrar extends ResourceRegistrar
{
    protected $resourceDefaults = ['index', 'create', 'store', 'edit', 'update', 'destroy'];

    protected function getResourceRouteName($resource, $method, $options): string
    {
        switch ($method) {
            case 'store':
                $method = 'create';

                break;
            case 'update':
                $method = 'edit';

                break;
        }

        return parent::getResourceRouteName($resource, $method, $options);
    }

    protected function addResourceEdit($name, $base, $controller, $options): Route
    {
        $uri = $this->getResourceUri($name) . '/' . static::$verbs['edit'] . '/{' . $base . '}';

        $action = $this->getResourceAction($name, $controller, 'edit', $options);

        return $this->router->get($uri, $action)->where($base, BaseHelper::routeIdRegex());
    }

    protected function addResourceUpdate($name, $base, $controller, $options): Route
    {
        $uri = $this->getResourceUri($name) . '/' . static::$verbs['edit'] . '/{' . $base . '}';

        $action = $this->getResourceAction($name, $controller, 'update', $options);

        return $this->router->post($uri, $action)->name($name . '.update')->where($base, BaseHelper::routeIdRegex());
    }

    protected function addResourceStore($name, $base, $controller, $options): Route
    {
        $uri = $this->getResourceUri($name) . '/' . static::$verbs['create'];

        $action = $this->getResourceAction($name, $controller, 'store', $options);

        return $this->router->post($uri, $action)->name($name . '.store');
    }

    protected function addResourceIndex($name, $base, $controller, $options): Route
    {
        $uri = $this->getResourceUri($name);

        unset($options['missing']);

        $action = $this->getResourceAction($name, $controller, 'index', $options);

        return $this->router->match(['GET', 'POST'], $uri, $action);
    }

    protected function addResourceDestroy($name, $base, $controller, $options): Route
    {
        return parent::addResourceDestroy($name, $base, $controller, $options)->where($base, BaseHelper::routeIdRegex());
    }
}
