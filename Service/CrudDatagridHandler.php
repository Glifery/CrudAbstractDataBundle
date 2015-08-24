<?php

namespace Glifery\CrudAbstractDataBundle\Service;

use Glifery\CrudAbstractDataBundle\DataObject\DataObjectInterface;
use Glifery\CrudAbstractDataBundle\Exception\ConfigException;
use Glifery\CrudAbstractDataBundle\Tools\Datagrid;
use Glifery\CrudAbstractDataBundle\Tools\FormTools;
use Symfony\Component\Form\Form;

//TODO: handle datagrid form
//TODO: register datagrid field types
class CrudDatagridHandler
{
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
                $value = $this->handleFieldMapping($object, $columnInfo);

                $columnName = $columnInfo['columnName'];
                $row[$columnName] = $value;
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

        $value = $object->$methodName();

        return $value;
    }
}