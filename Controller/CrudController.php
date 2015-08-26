<?php

namespace Glifery\CrudAbstractDataBundle\Controller;

use Glifery\CrudAbstractDataBundle\Crud\Crud;
use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Exception\ConfigException;
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

            return $this->redirectTo($object);
        }

        $view = $form->createView();
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->crud->getTemplate('form_theme'));

        return $this->render($this->crud->getTemplate('create'), array(
                'action' => 'create',
                'crud' => $this->crud,
                'form' => $view,
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

            return $this->redirectTo($object);
        }

        $view = $form->createView();
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->crud->getTemplate('form_theme'));

        return $this->render($this->crud->getTemplate('edit'), array(
                'action' => 'edit',
                'crud' => $this->crud,
                'form' => $view,
                'object' => $object
            ));
    }

    /**
     * @return RedirectResponse
     */
    public function deleteAction($identifier)
    {
        $criteria = $this->crud->createCriteriaByIdentifier($identifier);

        $this->crud->deleteObject($criteria);

        return $this->redirectTo();
    }

    /**
     * @param DataObjectInterface $object
     * @return RedirectResponse
     * @throws RouteException
     */
    protected function redirectTo(DataObjectInterface $object = null)
    {
        $url = null;
        if (null !== $this->get('request')->get('btn_update_and_list')) {
            $url = $this->crud->generateUrl('list');
        }
        if (null !== $this->get('request')->get('btn_create_and_list')) {
            $url = $this->crud->generateUrl('list');
        }
        if (null !== $this->get('request')->get('btn_update_and_edit')) {
            $url = $this->crud->generateObjectUrl('edit', $object);
        }
        if (null !== $this->get('request')->get('btn_create_and_edit')) {
            $url = $this->crud->generateObjectUrl('edit', $object);
        }

        if (null !== $this->get('request')->get('btn_create_and_show')) {
            $url = $this->crud->generateObjectUrl('show', $object);
        }

        if (!$url) {
            $url = $this->crud->generateUrl('list');
        }

        return $this->redirect($url);
    }

    /**
     * @param string $view
     * @param array $parameters
     * @param Response $response
     * @return Response
     * @throws ConfigException
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $parameters['base_template'] = isset($parameters['base_template']) ?
            $parameters['base_template'] :
            $this->crud->getTemplate('base_template');
        $parameters['admin_pool']    = $this->get('sonata.admin.pool');

        return parent::render($view, $parameters, $response);
    }
}