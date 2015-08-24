<?php

namespace Glifery\CrudAbstractDataBundle\ObjectManager;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Form\Type\DataFormTypeInterface;
use Glifery\CrudAbstractDataBundle\Tools\Datagrid;
use Glifery\CrudAbstractDataBundle\Tools\ObjectCollection;
use Glifery\CrudAbstractDataBundle\Tools\ObjectCriteria;

interface ObjectManagerInterface
{
    /** DataObjectInterface */
    public function getNewInstance();

    /**
     * @param ObjectCriteria $criteria
     * @param ObjectCollection $collection
     */
    function getList(ObjectCriteria $criteria, ObjectCollection $collection);

    /**
     * @param ObjectCriteria $criteria
     * @param ObjectCollection $collection
     */
    function getAmount(ObjectCriteria $criteria, ObjectCollection $collection);

    /**
     * @param ObjectCriteria $criteria
     * @return DataObjectInterface|null
     */
    function getObject(ObjectCriteria $criteria);

    /**
     * @param DataObjectInterface $object
     * @return DataObjectInterface
     */
    function createObject(DataObjectInterface $object);

    /**
     * @param ObjectCriteria $criteria
     * @param DataObjectInterface $object
     * @return DataObjectInterface
     */
    function updateObject(ObjectCriteria $criteria, DataObjectInterface $object);

//    /**
//     * @param ObjectCriteria $criteria
//     * @return bool
//     */
//    function deleteObject(ObjectCriteria $criteria);
}