<?php

namespace Glifery\CrudAbstractDataBundle\Service;

use Glifery\CrudAbstractDataBundle\Crud\Crud;
use Glifery\CrudAbstractDataBundle\Exception\GeneralException;
use Glifery\CrudAbstractDataBundle\Tools\CrudRoute;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class CrudRouteManager
{
    const TAG_CRUD = 'glifery_crud_abstract_data.crud';
    const CONTROLLER_NAME_REGEX = '/(.*)\\\(.*)Bundle\\\Controller\\\(.*)Controller::(.*)Action/';

    /** @var Router */
    private $router;

    /** @var array */
    private $crudRoutes;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;

        $this->collectCrudRoutes();
    }

    private function collectCrudRoutes()
    {
        /** @var $collection \Symfony\Component\Routing\RouteCollection */
        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();

        /** @var $route \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $routeName => $route)
        {
            $defaults = $route->getDefaults();

            if (!isset($defaults[self::TAG_CRUD])) continue;
            if (!isset($defaults['_controller'])) {
                throw new GeneralException(sprintf(
                        'Route %s doesn\'t contain \'_controller\' argument.',
                        $routeName
                    ));
            }

            $routeNameParts = $this->explodeRouteNameParts($defaults['_controller']);

            $crudRoute = new CrudRoute();
            $crudRoute->setRouteName($routeName);
            $crudRoute->setCrudName($defaults[self::TAG_CRUD]);
            $crudRoute->setControllerFullName($routeNameParts[0]);
            $crudRoute->setBundleName($routeNameParts[1] . $routeNameParts[2]);//TODO: check long namespaces
            $crudRoute->setControllerName($routeNameParts[3]);
            $crudRoute->setActionName($routeNameParts[4]);

            $this->crudRoutes[$crudRoute->getControllerFullName()] = $crudRoute;
        }
    }

    /**
     * @param string $controllerFullName
     * @return array
     */
    private function explodeRouteNameParts($controllerFullName)
    {
        $matches = array();
        preg_match(self::CONTROLLER_NAME_REGEX, $controllerFullName, $matches);

        return $matches;
    }

    /**
     * @param Crud $crud
     * @param string $actionName
     * @return CrudRoute|null
     */
    public function getCrudRoute(Crud $crud, $actionName)
    {
        $crudName = $crud->getCrudName();

        foreach ($this->crudRoutes as $crudRoute) {
            /** @var CrudRoute $crudRoute */

            if (($crudRoute->getCrudName() === $crudName) && ($crudRoute->getActionName() === $actionName)) return $crudRoute;
        }

        return null;
    }

    /**
     * @param CrudRoute $crudRoute
     * @param array $params
     * @return string
     */
    public function generateUrl(CrudRoute $crudRoute, array $params)
    {
        $routeName = $crudRoute->getRouteName();
        $url = $this->router->generate($routeName, $params);

        return $url;
    }
}