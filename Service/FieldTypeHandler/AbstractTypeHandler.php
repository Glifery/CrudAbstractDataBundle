<?php

namespace Glifery\CrudAbstractDataBundle\Service\FieldTypeHandler;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Exception\ConfigException;
use Glifery\CrudAbstractDataBundle\Tools\Field;

abstract class AbstractTypeHandler implements FieldTypeHandlerInterface
{
    /**
     * @return array
     */
    abstract protected function provideDefaultOptions();

    /**
     * @return array
     */
    abstract protected function provideRequiredOptions();

    /**
     * @param Field $field
     * @param DataObjectInterface $object
     */
    public function handleField(Field $field, DataObjectInterface $object)
    {
        $this->setDefaultOptions($field);
    }

    /**
     * @param Field $field
     * @throws ConfigException
     */
    protected function setDefaultOptions(Field $field)
    {
        $options = $field->getOptions();
        $defaultOptions = $this->provideDefaultOptions();
        $requiredOptions = $this->provideRequiredOptions();
        $requiredOptions = array_combine($requiredOptions, $requiredOptions);

        $resultOptions = array_merge($defaultOptions, $options);

        $unexistedOptions = array_diff_key($requiredOptions, $resultOptions);
        if (count($unexistedOptions)) {
            throw new ConfigException(sprintf(
                    'Field \'%s\' (with type \'%s\') must contain required options: \'%s\'.',
                    $field->getName(),
                    $field->getType(),
                    implode('\', \'', $unexistedOptions)
                ));
        }

        $field->setOptions($resultOptions);
    }
}