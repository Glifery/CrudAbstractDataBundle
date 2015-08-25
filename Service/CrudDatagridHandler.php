<?php

namespace Glifery\CrudAbstractDataBundle\Service;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Exception\ConfigException;
use Glifery\CrudAbstractDataBundle\Service\FieldTypeHandler\FieldTypeHandlerInterface;
use Glifery\CrudAbstractDataBundle\Tools\Datagrid;
use Glifery\CrudAbstractDataBundle\Tools\Field;
use Glifery\CrudAbstractDataBundle\Tools\FormTools;
use Symfony\Component\Form\Form;

//TODO: handle datagrid form
//TODO: register datagrid field types
class CrudDatagridHandler
{
    /** @var array */
    private $fieldTypeHandlers;

    public function __construct()
    {
        $this->fieldTypeHandlers = array();
    }

    /**
     * @param string $name
     * @param FieldTypeHandlerInterface $fieldTypeHandler
     */
    public function registerFieldType($name, FieldTypeHandlerInterface $fieldTypeHandler, $template)
    {
        $this->fieldTypeHandlers[$name] = array(
            'handler' => $fieldTypeHandler,
            'template' => $template
        );
    }

    /**
     * @param Form $datagridForm
     * @return array|null
     */
    public function getFilterData(Form $datagridForm)
    {
        $datagridFilter = FormTools::getFormAsArray($datagridForm);

        return $datagridFilter;
    }

    /**
     * @param Datagrid $datagrid
     * @return array
     * @throws ConfigException
     */
    public function generateDatagridTable(Datagrid $datagrid)
    {
        //TODO: sort and filter

        $table = array(
            'header' => array(),
            'body' => array()
        );

        foreach ($datagrid->getFieldMapper()->getColumns() as $columnInfo) {
            $columnName = $columnInfo['columnName'];
            $table['header'][$columnName] = isset($columnInfo['options']['label']) ? $columnInfo['options']['label'] : $columnName;
        }

        $collection = $datagrid->getCollection();
        foreach ($collection->getDataObjects() as $object) {
            $row = array();

            foreach ($datagrid->getFieldMapper()->getColumns() as $columnInfo) {
                $fieldObject = $this->handleFieldMapping($object, $columnInfo);

                $columnName = $columnInfo['columnName'];
                $row[$columnName] = $fieldObject;
            }

            $table['body'][] = $row;
        }

        return $table;
    }

    /**
     * @param DataObjectInterface $object
     * @param array $columnInfo
     * @return mixed
     * @throws ConfigException
     */
    private function handleFieldMapping(DataObjectInterface $object, array $columnInfo)
    {
        $columnName = $columnInfo['columnName'];
        $methodName = 'get' . ucfirst($columnName);

        if (!method_exists($object, $methodName)) {
            throw new ConfigException(sprintf(
                    'Tryin to map unexisted method %s of object.',
                    $methodName,
                    get_class($object)
                ));
        }

        $rawValue = $object->$methodName();

        $fieldTypeHandlerInfo = $this->matchHandlerForField($columnInfo['columnType']);
        /** @var FieldTypeHandlerInterface $fieldTypeHandler */
        $fieldTypeHandler = $fieldTypeHandlerInfo['handler'];
        $template = $fieldTypeHandlerInfo['template'];

        $field = new Field();
        $field->setName($columnInfo['columnName']);
        $field->setType($columnInfo['columnType']);
        $field->setValue($rawValue);
        $field->setOptions($columnInfo['options']);
        $field->setTemplate(isset($columnInfo['options']['template']) ? $columnInfo['options']['template'] : $template);
        $field->setHandler($fieldTypeHandler);

        $fieldTypeHandler->handleField($field, $object);

        return $field;
    }

    /**
     * @param $columnType
     * @return array
     * @throws ConfigException
     */
    private function matchHandlerForField($columnType)
    {
        foreach ($this->fieldTypeHandlers as $handlerType => $fieldTypeHandlerInfo) {
            if ($columnType === $handlerType) return $fieldTypeHandlerInfo;
        }

        throw new ConfigException(sprintf(
                'Handler for column with type \'%s\' not found. You must register FieldTypeHandler.',
                $columnType
            ));
    }
}