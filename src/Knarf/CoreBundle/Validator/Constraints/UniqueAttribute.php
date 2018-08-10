<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * Description of UniqueAttribute
 *
 * @author franck
 *
 * @Annotation
 */
class UniqueAttribute extends Constraint
{
    public $message = 'The %property% "%string%" is already in use.';
    public $repository;
    public $property;
    public function __construct($options = null)
    {
        parent::__construct($options);
        if (null === $this->repository || null === $this->property) {
            throw new MissingOptionsException(sprintf('The options "repository" and "property" must be given for constraint %s', __CLASS__), array('repository', 'property'));
        }
    }
    public function validatedBy()
    {
        return 'unique_attribute_validator';
    }
}
