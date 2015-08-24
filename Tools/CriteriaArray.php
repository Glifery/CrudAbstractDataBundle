<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

class CriteriaArray
{
    /** @var array */
    private $criteria;

    public function __construct()
    {
        $this->criteria = array();
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->criteria;
    }

    /**
     * @param array $array
     * @return $this
     */
    public function setAll($array)
    {
        if (is_array($array)) {
            $this->criteria = $array;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function clearAll()
    {
        $this->criteria = array();

        return $this;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function get($name)
    {
        return isset($this->criteria[$name]) ? $this->criteria[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->criteria[$name] = $value;

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->criteria[$name]);
    }
}