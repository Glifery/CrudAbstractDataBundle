<?php

namespace Glifery\CrudAbstractDataBundle\Service;

class CrudTemplateSet
{
    /** @var array */
    private $templates;

    public function __construct()
    {
        $this->templates = array();
    }

    /**
     * @param string $name
     * @param string $template
     */
    public function setTemplate($name, $template)
    {
        $this->templates[$name] = $template;
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }
}