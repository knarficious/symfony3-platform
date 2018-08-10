<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\CoreBundle\Validator\Constraints;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Description of UniqueAttributeValidator
 *
 * @author franck
 */
class UniqueAttributeValidator extends ConstraintValidator 
{
    /**
     * @param ObjectManager $manager
     */
    protected $manager;
    
    /**
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint) 
    {
         if(!$constraint instanceof UniqueAttribute) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UniqueAttribute');
        }
        // throws exception if not successful
        $repository = $this->manager->getRepository($constraint->repository);
        if(count($repository->findBy(array($constraint->property => $value)))) {
            $this->context->addViolation(
                $constraint->message,
                array(
                    '%property%' => $constraint->property,
                    '%string%' => $value
                )
            );
        }
    }

}
