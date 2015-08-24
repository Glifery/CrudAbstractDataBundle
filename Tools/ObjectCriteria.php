<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

class ObjectCriteria
{
    /** @var CriteriaArray */
    private $filter;

    /** @var CriteriaArray */
    private $sort;

    /** @var CriteriaArray */
    private $order;

    /** @var CriteriaArray */
    private $field;

    /** @var CriteriaArray */
    private $offset;

    public function __construct()
    {
        $this->filter = new CriteriaArray();
        $this->sort = new CriteriaArray();
        $this->order = new CriteriaArray();
        $this->field = new CriteriaArray();
        $this->offset = new CriteriaArray();
    }

    /**
     * @return CriteriaArray
     */
    public function filter()
    {
        return $this->filter;
    }

    /**
     * @return CriteriaArray
     */
    public function sort()
    {
        return $this->sort;
    }

    /**
     * @return CriteriaArray
     */
    public function order()
    {
        return $this->order;
    }

    /**
     * @return CriteriaArray
     */
    public function field()
    {
        return $this->field;
    }

    /**
     * @return CriteriaArray
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * @param $identifier
     * @return $this
     */
    public function oneWithIdentifier($identifier)
    {
        $this->filter()->set('_identifier', $identifier);
        $this->offset()->set('amount', 1);

        return $this;
    }
}