<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Description of EmailBlackList
 *
 * @author franck
 *
 * @Annotation
 */
class EmailBlackList extends Constraint
{
    public $message = 'Les services de mails jetables ne sont pas autorisés.';
    
    public function validatedBy()
    {
        return 'email_black_list';
    }
}
