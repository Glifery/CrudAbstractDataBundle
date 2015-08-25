<?php

namespace Glifery\CrudAbstractDataBundle\Service\FieldTypeHandler;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Tools\Field;

interface FieldTypeHandlerInterface
{
    /**
     * @param Field $field
     * @param DataObjectInterface $object
     */
    public function handleField(Field $field, DataObjectInterface $object);
}