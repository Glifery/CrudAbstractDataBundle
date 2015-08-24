<?php

namespace Glifery\CrudAbstractDataBundle\Controller;

use Glifery\CrudAbstractDataBundle\Crud\Crud;
use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Exception\RouteException;
use Glifery\CrudAbstractDataBundle\Form\Type\DataFormTypeInterface;
use Glifery\CrudAbstractDataBundle\Tools\CrudRoute;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CrudController extends Controller
{
    const CONTROLLER_NAME_REGEX = '/(.*)\\\(.*)Bundle\\\Controller\\\(.*)Controller::(.*)Action/';

    /** @var Crud */
    protected $crud;

    /**
     * @param Crud $crud
     */
    public function initAsCrudController(Crud $crud)
    {
        $this->crud = $crud;
    }

    public function listAction()
    {
        $request = $this->get('request');
        $filterForm = $this->crud->createFilterFromRequest($request);
        $criteria = $this->crud->createCriteriaByFilter($filterForm, $request);
        $collection = $this->crud->getObjectCollection($criteria);
        $datagrid = $this->crud->createDatagrid($criteria, $collection);

        return $this->render($this->crud->getTemplate('list'), array(
                'action' => 'list',
                'crud' => $this->crud,
                'datagrid' => $datagrid
            ));
    }

    public function createAction()
    {
        $object = $this->crud->getNewInstance();
        $form = $this->crud->getObjectForm($object);

        $request = $this->get('request');
        $form->handleRequest($request);
        if ($form->isValid()) {
            $object = $this->crud->createObject($object);

            return $this->redirectTo('edit', $object);
        }

        return $this->render($this->crud->getTemplate('create'), array(
                'action' => 'create',
                'crud' => $this->crud,
                'form' => $form->createView(),
                'object' => $object
            ));
    }

    public function editAction($identifier)
    {
        $criteria = $this->crud->createCriteriaByIdentifier($identifier);
        $object = $this->crud->getObject($criteria);

        $form = $this->crud->getObjectForm($object);

        $request = $this->get('request');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $object = $this->crud->updateObject($criteria, $object);

            return $this->redirectTo('edit', $object);
        }

        return $this->render($this->crud->getTemplate('edit'), array(
                'action' => 'edit',
                'crud' => $this->crud,
                'form' => $form->createView(),
                'object' => $object
            ));
    }

    public function deleteAction()
    {

    }

    /**
     * @param string $actionName
     * @param DataObjectInterface $object
     * @return RedirectResponse
     * @throws RouteException
     */
    protected function redirectTo($actionName, DataObjectInterface $object = null)
    {
        //TODO: able redirect to another CRUD class

        $crudRouteManager = $this->get('glifery_crud_abstract_data.service.crud_route_manager');
        if (!$crudRoute = $crudRouteManager->getCrudRoute($this->crud, $actionName)) {
            if (!$crudRoute = $crudRouteManager->getCrudRoute($this->crud, 'list')) {
                throw new RouteException(sprintf('Action \'list\' for Crud \'%s\' not found.', $this->crud->getCrudName()));
            }
        }

        switch ($actionName) {
            case 'edit':
                if ($object) {
                    $routeParams = array('identifier' => $object->identifier());
                } else {
                    return $this->redirectTo('list');
                }
                break;
            default:
                $routeParams = array();
                break;
        }

        $url = $this->generateUrl($crudRoute->getRouteName(), $routeParams);

        return $this->redirect($url);
    }

    /**
     * {@inheritdoc}
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
//        $parameters['admin']         = isset($parameters['admin']) ?
//            $parameters['admin'] :
//            $this->admin;
        $parameters['base_template'] = isset($parameters['base_template']) ?
            $parameters['base_template'] :
            $this->crud->getTemplate('base_template');
        $parameters['admin_pool']    = $this->get('sonata.admin.pool');

        return parent::render($view, $parameters, $response);
    }
}