<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;


/**
 * Description of EmailBlackListValidator
 *
 * @author franck
 */
class EmailBlackListValidator extends ConstraintValidator
{
    private $blackList;
    
    public function setBlackList(array $blackList)
    {
        $this->blackList = $blackList;
    }
    
    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof EmailBlackList) {
            throw new UnexpectedTypeException($constraint, EmailBlackList::class);
        }
        $domainArray = preg_split("/@/", $value);
        if (count($domainArray) > 1) {
            $domain = $domainArray[1];
            if (!is_null($this->blackList) && in_array($domain, $this->blackList)) {
                $this->context->addViolation($constraint->message);
            }
        }
    }
}
