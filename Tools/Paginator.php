<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

class Paginator
{
    /** @var bool */
    private $enabled;

    /** @var array */
    private $pages;

    /** @var integer */
    private $current;

    /** @var integer */
    private $amount;

    public function __construct()
    {
        $this->enabled = false;
        $this->pages = array();
        $this->current = 0;
        $this->amount = 0;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param int $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
    }

    /**
     * @return array
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param array $page
     */
    public function addPage(array $page)
    {
        if ($page['current']) {
            $this->current = $page['index'];
        }

        $this->enabled = true;
        $this->amount++;

        $this->pages[$page['index']] = $page;
    }

    /**
     * @return array|null
     */
    public function getFirstPage()
    {
        if (isset($this->pages[1])) {
            return $this->pages[1];
        }

        return null;
    }

    /**
     * @return array|null
     */
    public function getLastPage()
    {
        $lastIndex = 0;
        foreach ($this->pages as $index => $page) {
            if ($index > $lastIndex) {
                $lastIndex = $index;
            }
        }

        return $lastIndex ? $this->pages[$lastIndex] : null;
    }
}