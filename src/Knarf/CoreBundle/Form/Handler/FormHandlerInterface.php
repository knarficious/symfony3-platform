<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\CoreBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author franck
 */
interface FormHandlerInterface 
{
     /**
     * handles the form
     *
     * @param FormInterface $form
     * @param Request $request
     * @param array $options
     */
    public function handle(FormInterface $form, Request $request, array $options = null);
}
