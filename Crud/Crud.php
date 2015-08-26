<?php

namespace Glifery\CrudAbstractDataBundle\Crud;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Exception\ConfigException;
use Glifery\CrudAbstractDataBundle\Exception\RouteException;
use Glifery\CrudAbstractDataBundle\ObjectManager\ObjectManagerInterface;
use Glifery\CrudAbstractDataBundle\Service\CrudPool;
use Glifery\CrudAbstractDataBundle\Tools\CrudRoute;
use Glifery\CrudAbstractDataBundle\Tools\Datagrid;
use Glifery\CrudAbstractDataBundle\Tools\FieldMapper;
use Glifery\CrudAbstractDataBundle\Tools\FormTools;
use Glifery\CrudAbstractDataBundle\Tools\ObjectCollection;
use Glifery\CrudAbstractDataBundle\Tools\ObjectCriteria;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;

class Crud extends ContainerAware
{
    /** @var string */
    protected $crudName;

    /** @var */
    protected $label;

    /** @var CrudPool */
    protected $crudPool;

    /** @var Datagrid */
    protected $datagrid;

    /** @var ObjectManagerInterface */
    protected $objectManager;

    /** @var array */
    protected $templates;

    /**
     * @param FormBuilder $formBuilder
     */
    protected function configureFormFields(FormBuilder $formBuilder)
    {

    }

    protected function configureListFields(FieldMapper $fieldMapper)
    {

    }

    /**
     * @param FormBuilder $formBuilder
     */
    protected function configureDatagridFilters(FormBuilder $formBuilder)
    {

    }

    /**
     * @param $crudName
     */
    public function initAsCrud($crudName, CrudPool $crudPool)
    {
        $this->crudName = $crudName;
        $this->crudPool = $crudPool;

        //TODO: replace default templates from here
        $this->templates = array(
            'base_template' => 'GliferyCrudAbstractDataBundle::standard_layout.html.twig',
            'list' => 'GliferyCrudAbstractDataBundle:CRUD:list.html.twig',
            'create' => 'GliferyCrudAbstractDataBundle:CRUD:edit.html.twig',
            'edit' => 'GliferyCrudAbstractDataBundle:CRUD:edit.html.twig',
            'inner_list_row' => 'GliferyCrudAbstractDataBundle:CRUD:list_inner_row.html.twig',
            'pager_results' => 'GliferyCrudAbstractDataBundle:Pager:results.html.twig',
            'pager_links' => 'GliferyCrudAbstractDataBundle:Pager:links.html.twig',
            'form_theme' => 'GliferyCrudAbstractDataBundle:Form:form_admin_fields.html.twig'
        );
    }

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function registerObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return string
     */
    public function getCrudName()
    {
        return $this->crudName;
    }

    /**
     * @param string $crudName
     */
    public function setCrudName($crudName)
    {
        $this->crudName = $crudName;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return CrudPool
     */
    public function getCrudPool()
    {
        return $this->crudPool;
    }

    /**
     * @param string $name
     * @param string $template
     */
    public function setTemplate($name, $template)
    {
        $this->templates[$name] = $template;
    }

    /**
     * @param string $name
     * @throws ConfigException
     */
    public function getTemplate($name)
    {
        if (isset($this->templates[$name])) {
            return $this->templates[$name];
        }

        throw new ConfigException(sprintf(
                'Crud template with name \'%s\' not found, You must define method setTemplate(\'%s\', <template>) call from Crud config',
                $name,
                $name
            ));
    }

    /**
     * @return DataObjectInterface
     */
    public function getNewInstance()
    {
        return $this->objectManager->getNewInstance();
    }

    /**
     * @param Request $request
     * @return Form
     */
    public function createFilterFromRequest(Request $request)
    {
        $datagridForm = $this->getDatagridForm();
        $datagridForm->handleRequest($request);

        return $datagridForm;
    }

    /**
     * @param Form $datagridForm
     * @return ObjectCriteria
     */
    public function createCriteriaByFilter(Form $datagridForm, Request $request)
    {
        $datagridFilter = $this->crudPool->getDatagridHandler()->getFilterData($datagridForm);
        $datagridOffset = $this->crudPool->getPagination()->getOffset($request);

        $criteria = new ObjectCriteria();
        $criteria->filter()->setAll($datagridFilter);
        $criteria->offset()->setAll($datagridOffset);

        return $criteria;
    }

    /**
     * @param string $identifier
     * @return ObjectCriteria
     */
    public function createCriteriaByIdentifier($identifier)
    {

        $criteria = new ObjectCriteria();
        $criteria->oneWithIdentifier($identifier);

        $form = $this->getObjectForm();
        $fields = FormTools::getFormMappedFields($form);
        $criteria->field()->setAll($fields);

        return $criteria;
    }

    /**
     * @param ObjectCriteria $criteria
     * @param ObjectCollection $collection
     * @return Datagrid
     */
    public function createDatagrid(ObjectCriteria $criteria, ObjectCollection $collection)
    {
        $fieldMapper = new FieldMapper();
        $this->configureListFields($fieldMapper);

        $datagrid = new Datagrid();
        $datagrid->setFieldMapper($fieldMapper);
        $datagrid->setCriteria($criteria);
        $datagrid->setFilter($fieldMapper);
        $datagrid->setCollection($collection);

        $table = $this->crudPool->getDatagridHandler()->generateDatagridTable($datagrid);
        $datagrid->setResults($table);

        $paginator = $this->crudPool->getPagination()->getPaginator($datagrid);
        $datagrid->setPaginator($paginator);

        return $datagrid;
    }

    /**
     * @param ObjectCriteria $criteria
     * @return DataObjectInterface|null
     */
    public function getObject(ObjectCriteria $criteria)
    {
        $object = $this->objectManager->getObject($criteria);

        return $object;
    }

    public function getObjectCollection(ObjectCriteria $criteria)
    {
        $collection = new ObjectCollection();
        $this->objectManager->getList($criteria, $collection);
        $this->objectManager->getAmount($criteria, $collection);

        return $collection;
    }

    /**
     * @param DataObjectInterface $object
     * @return DataObjectInterface
     */
    public function createObject(DataObjectInterface $object)
    {
        $object = $this->objectManager->createObject($object);

        return $object;
    }

    /**
     * @param ObjectCriteria $criteria
     * @param DataObjectInterface $object
     * @return DataObjectInterface
     */
    public function updateObject(ObjectCriteria $criteria, DataObjectInterface $object)
    {
        $object = $this->objectManager->updateObject($criteria, $object);

        return $object;
    }

    /**
     * @param ObjectCriteria $criteria
     * @return bool
     */
    public function deleteObject(ObjectCriteria $criteria)
    {
        $isDeleted = $this->objectManager->deleteObject($criteria);

        return $isDeleted;
    }

    /**
     * @param DataObjectInterface $object
     * @return Form
     */
    public function getObjectForm(DataObjectInterface $object = null)
    {
        $formBuilder = $this->crudPool->getFormFactory()->createFormBuilder($object);
        $this->configureFormFields($formBuilder);
        $form = $formBuilder->getForm();

        return $form;
    }

    /**
     * @param DataObjectInterface $object
     * @return mixed
     */
    public function id(DataObjectInterface $object)
    {
        return $object->identifier();
    }

    /**
     * @param $routeName
     * @return bool
     */
    public function hasRoute($routeName)
    {
        return ($this->crudPool->getRouteManager()->getCrudRoute($this, $routeName)) ? true : false;
    }

    /**
     * @param string $routeName
     * @param array $params
     * @return string
     * @throws RouteException
     */
    public function generateUrl($routeName, array $params = array())
    {
        $crudRoute = $this->getCrudRouteWithName($routeName);
        $url = $this->crudPool->getRouteManager()->generateUrl($crudRoute, $params);

        return $url;
    }

    /**
     * @param string $routeName
     * @param DataObjectInterface $object
     * @return string
     */
    public function generateObjectUrl($routeName, DataObjectInterface $object)
    {
        $crudRoute = $this->getCrudRouteWithName($routeName);
        $url = $this->crudPool->getRouteManager()->generateUrl($crudRoute, array('identifier' => $object->identifier()));

        return $url;
    }

    /**
     * @param string $routeName
     * @return CrudRoute
     * @throws RouteException
     */
    protected function getCrudRouteWithName($routeName)
    {
        if (!$crudRoute = $this->crudPool->getRouteManager()->getCrudRoute($this, $routeName)) {
            throw new RouteException(sprintf(
                    'There is no route with name \'%s\' for Crud class \'%s\'.',
                    $routeName,
                    $this->getCrudName()
                ));
        }

        return $crudRoute;
    }

    /**
     * @return Form
     */
    protected function getDatagridForm()
    {
        $formBuilder = $this->crudPool->getFormFactory()->createFormBuilder();
        $this->configureDatagridFilters($formBuilder);
        $form = $formBuilder->getForm();

        return $form;
    }
}