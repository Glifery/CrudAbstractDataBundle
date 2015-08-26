<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Service\FieldTypeHandler\FieldTypeHandlerInterface;

class Field
{
    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var mixed */
    private $value;

    /** @var array */
    private $options;

    /** @var string */
    private $template;

    /** @var DataObjectInterface */
    private $object;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return DataObjectInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param DataObjectInterface $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }
}