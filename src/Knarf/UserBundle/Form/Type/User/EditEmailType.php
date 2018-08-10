<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Form\Type\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Knarf\UserBundle\Entity\Manager\Interfaces\UserManagerInterface;



/**
 * Description of EditEmailType
 *
 * @author franck
 */
class EditEmailType extends AbstractType 
{
    /**
     *
     * @var UserManagerInterface $userManager
     */
    private $userManager;
    
    /**
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) 
            {
        $builder->add('email', EmailType::class);
        $builder->add('Register', SubmitType::class, array(
            'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
            'label' => 'user.registration.register'
        ));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Knarf\UserBundle\Entity\User\Profile',
        ]);
    }
}
