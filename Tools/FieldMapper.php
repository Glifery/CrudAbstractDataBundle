<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

class FieldMapper
{
    /** @var array */
    private $columns;

    public function __construct()
    {
        $this->columns = array();
    }

    /**
     * @param string $columnName
     * @param string $columnType
     * @param array $options
     * @return $this
     */
    public function add($columnName, $columnType = '', $options = array())
    {
        $this->columns[$columnName] = array(
            'columnName' => $columnName,
            'columnType' => $columnType,
            'options' => $options,
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }
}