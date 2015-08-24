<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;

class ObjectCollection
{
    private $dataObjects;

    private $amount;

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