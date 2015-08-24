<?php

namespace Glifery\CrudAbstractDataBundle\Tools;

use Symfony\Component\Form\Form;

class FormTools
{
    /**
     * @param Form $form
     * @return array
     */
    static public function getFormMappedFields(Form $form)
    {
        $formTypeNames = array();

        $formTypes = $form->getIterator();
        foreach ($formTypes as $formType) {
            if (!is_a($formType, 'Symfony\Component\Form\Form')) continue;
            /** @var Form $formType */

            if (!$formType->getConfig()->getMapped()) continue;

            $formTypeNames[] = $formType->getName();
        }

        return $formTypeNames;
    }

    /**
     * @param Form $form
     * @return array
     */
    static public function getFormAsArray(Form $form)
    {
        $formTypesData = array();

        $formTypes = $form->getIterator();
        foreach ($formTypes as $formType) {
            /** @var Form $formType */

            $formTypesData[$formType->getName()] = $formType->getData();
        }

        return $formTypesData;
    }
}