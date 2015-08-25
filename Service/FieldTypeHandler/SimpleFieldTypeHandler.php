<?php

namespace Glifery\CrudAbstractDataBundle\Service\FieldTypeHandler;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Tools\Field;

class SimpleFieldTypeHandler implements FieldTypeHandlerInterface
{
    /**
     * @param Field $field
     * @param DataObjectInterface $object
     */
    public function handleField(Field $field, DataObjectInterface $object)
    {

    }
}