<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

class CrudRoute
{
    /** @var string */
    private $routeName;

    /** @var string */
    private $crudName;

    /** @var string */
    private $controllerFullName;

    /** @var string */
    private $bundleName;

    /** @var string */
    private $controllerName;

    /** @var string */
    private $actionName;

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @param string $actionName
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return $this->bundleName;
    }

    /**
     * @param string $bundleName
     */
    public function setBundleName($bundleName)
    {
        $this->bundleName = $bundleName;
    }

    /**
     * @return string
     */
    public function getControllerFullName()
    {
        return $this->controllerFullName;
    }

    /**
     * @param string $controllerFullName
     */
    public function setControllerFullName($controllerFullName)
    {
        $this->controllerFullName = $controllerFullName;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @param string $controllerName
     */
    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;
    }

    /**
     * @return string
     */
    public function getCrudName()
    {
        return $this->crudName;
    }

    /**
     * @param string $crudName
     */
    public function setCrudName($crudName)
    {
        $this->crudName = $crudName;
    }
}