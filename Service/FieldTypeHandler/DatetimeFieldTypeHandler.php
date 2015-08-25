<?php

namespace Glifery\CrudAbstractDataBundle\Service\FieldTypeHandler;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Tools\Field;

class DatetimeFieldTypeHandler extends AbstractTypeHandler
{
    const OPTION_DEFAULT_FORMAT = 'd.m.Y';

    /**
     * @return array
     */
    protected function provideDefaultOptions()
    {
        return array(
            'format' => self::OPTION_DEFAULT_FORMAT
        );
    }

    /**
     * @return array
     */
    protected function provideRequiredOptions()
    {
        return array();
    }
}