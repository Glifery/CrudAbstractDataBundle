<?php

namespace Glifery\CrudAbstractDataBundle\Service\FieldTypeHandler;

use Doctrine\Common\Persistence\ObjectManager;
use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Exception\ConfigException;
use Glifery\CrudAbstractDataBundle\Tools\Field;

class EntityFieldTypeHandler extends AbstractTypeHandler
{
    /** @var ObjectManager */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return array
     */
    protected function provideDefaultOptions()
    {
        return array(
            'em' => $this->objectManager,
            'property' => 'id'
        );
    }

    /**
     * @param Field $field
     * @param DataObjectInterface $object
     * @throws ConfigException
     */
    public function handleField(Field $field, DataObjectInterface $object)
    {
        if (!$field->getValue()) return;

        $this->setDefaultOptions($field);

        $options = $field->getOptions();
        /** @var ObjectManager $objectManager */
        $objectManager = $options['em'];
        $class = $options['class'];

        if (!$repo = $objectManager->getRepository($class)) {
            throw new ConfigException(sprintf(
                    'Repository for class \'%s\' doesn\'t exist.',
                    $class
                ));
        }

        $property = $options['property'];
        $criteria = array(
            $property => $field->getValue()
        );

        $entity = $repo->findOneBy($criteria);
        $field->setValue($entity);
    }

    /**
     * @return array
     */
    protected function provideRequiredOptions()
    {
        return array('class');
    }
}