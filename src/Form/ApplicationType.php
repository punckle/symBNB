<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * Fonction créée pour rendre DRY la fonction buildForm
     * @param $label
     * @param $placeholder
     * @return array
     */
    protected function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge ([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder]
        ], $options);
    }
}