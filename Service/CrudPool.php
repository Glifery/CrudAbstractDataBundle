<?php

namespace Glifery\CrudAbstractDataBundle\Service;

use Glifery\CrudAbstractDataBundle\Crud\Crud;

class CrudPool
{
    /** @var CrudRouteManager */
    private $routeManager;

    /** @var CrudFormFactory */
    private $formFactory;

    /** @var CrudDatagridHandler */
    private $datagridHandler;

    /** @var array */
    private $cruds;

    /** @var CrudPagination */
    private $pagination;

    /**
     * @param CrudRouteManager $crudRouteManager
     * @param CrudFormFactory $crudFormFactory
     * @param CrudDatagridHandler $crudDatagridHandler
     */
    public function __construct(CrudRouteManager $crudRouteManager, CrudFormFactory $crudFormFactory, CrudDatagridHandler $crudDatagridHandler, CrudPagination $crudPagination)
    {
        $this->routeManager = $crudRouteManager;
        $this->formFactory = $crudFormFactory;
        $this->datagridHandler = $crudDatagridHandler;
        $this->pagination = $crudPagination;

        $this->cruds = array();
    }

    /**
     * @param Crud $crud
     */
    public function registerCrud($crudName, Crud $crud)
    {
        $this->cruds[$crudName] = $crud;
    }

    /**
     * @return CrudRouteManager
     */
    public function getRouteManager()
    {
        return $this->routeManager;
    }

    /**
     * @return CrudFormFactory
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    /**
     * @return CrudDatagridHandler
     */
    public function getDatagridHandler()
    {
        return $this->datagridHandler;
    }

    /**
     * @return CrudPagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }
}