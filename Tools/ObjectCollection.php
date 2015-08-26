<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;

class ObjectCollection
{
    /** @var array */
    private $dataObjects;

    /** @var integer */
    private $amount;

    public function __construct()
    {
        $this->dataObjects = array();
        $this->amount = 0;
    }

    /**
     * @param DataObjectInterface $dataObject
     */
    public function addDataObject(DataObjectInterface $dataObject)
    {
        $this->dataObjects[] = $dataObject;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getDataObjects()
    {
        return $this->dataObjects;
    }
}