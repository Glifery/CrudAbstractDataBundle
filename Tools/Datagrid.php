<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

use Symfony\Component\Form\Form;

class Datagrid
{
    /** @var FieldMapper */
    private $fieldMapper;

    /** @var ObjectCriteria */
    private $criteria;

    /** @var Form */
    private $filter;

    /** @var ObjectCollection */
    private $collection;

    /** @var array */
    private $results;

    /** @var  Paginator */
    private $paginator;

    /**
     * @return FieldMapper
     */
    public function getFieldMapper()
    {
        return $this->fieldMapper;
    }

    /**
     * @param FieldMapper $fieldMapper
     */
    public function setFieldMapper($fieldMapper)
    {
        $this->fieldMapper = $fieldMapper;
    }

    /**
     * @return ObjectCriteria
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param ObjectCriteria $criteria
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @return Form
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param Form $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return ObjectCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param ObjectCollection $collection
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param array $table
     */
    public function setResults($table)
    {
        $this->results = $table;
    }

    /**
     * @return Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * @param Paginator $paginator
     */
    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
    }
}