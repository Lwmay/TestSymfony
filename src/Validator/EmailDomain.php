<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * @Annotation
 */
class EmailDomain extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The value "{{ value }}" is not valid.';

    public $blocked = [];                                   // On précise que $blocked est une propriètè.

    public function __construct($options = [])              // On précise que le constructeur reçoit une liste d'options.
    {
        parent::__construct($options);
        if (!is_array($options['blocked'])){                    // Si $option['blocked'] n'est pas un tableau
            throw new ConstraintDefinitionException('The "blocked" option must be an array of blocked domains.');
        }
    }

    /**
     * @return array|string[] Liste d'options
     * Quand on créé une contrainte, on a la possibilité d'enregistrer une liste d'options qui sont nécessaire.
     */
    public function getRequiredOptions()
    {
        return ['blocked'];
    }
}
