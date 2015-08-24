<?php

namespace Glifery\CrudAbstractDataBundle\Service;

use Symfony\Component\Form\FormFactory;

class CrudFormFactory
{
    /** @var FormFactory */
    private $formFactory;

    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param object|null $data
     * @param array $options
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function createFormBuilder($data = null, array $options = array())
    {
        return $this->formFactory->createBuilder('form', $data, $options);
    }
}