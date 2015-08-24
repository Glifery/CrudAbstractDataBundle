<?php

namespace Glifery\CrudAbstractDataBundle\EventListener;

use Glifery\CrudAbstractDataBundle\Controller\CrudController;
use Glifery\CrudAbstractDataBundle\Exception\ConfigException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Router;

class ControllerEventHandler
{
    const ROUTE_CRUD_ARGUMENT_NAME = 'glifery_crud_abstract_data.crud';
    const CONTROLLER_DEFAULT_FULL_NAME = '\Glifery\CrudAbstractDataBundle\Controller\CrudController';

    /** @var Router */
    private $router;

    /** @var Container */
    private $container;

    public function __construct(Router $router, Container $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * @param FilterControllerEvent $event
     * @throws ConfigException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $routeCollection = $this->router->getRouteCollection();
        $routeName = $event->getRequest()->get('_route');
        if ((!$currentRoute = $routeCollection->get($routeName)) || (!$crudServiceName = $currentRoute->getDefault(self::ROUTE_CRUD_ARGUMENT_NAME))) {
            return false;
        }

        $controllerInfo = $event->getController();
        /** @var CrudController $controller */
        $controller = $controllerInfo[0];

        $this->checkIsControllerInheritCrud($controller);
        $this->registerCrudInController($crudServiceName, $controller);
    }

    /**
     * @param Controller $controller
     * @throws ConfigException
     */
    private function checkIsControllerInheritCrud(Controller $controller)
    {
        if (!is_a($controller, self::CONTROLLER_DEFAULT_FULL_NAME)) {
            throw new ConfigException(sprintf(
                    'Controller %s contains \'%s\' argument and must extend %s.',
                    get_class($controller),
                    self::ROUTE_CRUD_ARGUMENT_NAME,
                    self::CONTROLLER_DEFAULT_FULL_NAME
                ));
        }
    }

    /**
     * @param $crudServiceName
     * @param CrudController $controller
     * @throws ConfigException
     */
    private function registerCrudInController($crudServiceName, CrudController $controller)
    {
        if (!$crud = $this->container->get($crudServiceName)) {
            throw new ConfigException(sprintf(
                    'Crud service with name %s not found in service container.',
                    $crudServiceName
                ));
        }

        $controller->initAsCrudController($crud);
    }
}