<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Form\Type\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Knarf\UserBundle\Entity\Password\ResetPassword;
use Knarf\UserBundle\Entity\Manager\Interfaces\UserManagerInterface;

/**
 * Description of ResetPasswordType
 *
 * @author franck
 */
class ResetPasswordType extends AbstractType 
{
    /**
     *
     * @var UserManagerInterface $userManager
     */
    private $userManager;
    
    /**
     *
     * @var RequestStack $request
     */
    private $request;
    
    /**
     * ResetPasswordType constructor.
     * @param UserManagerInterface $userManager
     * @param RequestStack $request
     */
    public function __construct(UserManagerInterface $userManager, RequestStack $request)
    {
        $this->userManager = $userManager;
        $this->request = $request;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, array(
            'first_name'  => 'password',
            'second_name' => 'confirm',
            'type'        => PasswordType::class,
            'first_options' => [
                'label' => 'user.reset_password.new_password',
            ],
            'second_options' => [
                'label' => 'user.reset_password.repeat_new_password',
            ]
        ));
        $builder->add('Reset Password', SubmitType::class, array(
            'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
            'label' => 'user.reset_password.button'
        ));
        $builder->addEventListener(
        FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                if (!$data instanceof ResetPassword) {
                    throw new \RuntimeException('ResetPassword instance required.');
                }
                $token = $this->request->getCurrentRequest()->get('token');
                if (!$token) {
                   throw new \Exception('Missing Token.');
                }
                $user = $this->userManager->getUserByConfirmationToken($token);
                if (!$user) {
                   throw new \Exception('User not identified in our database with this token.');
                }
            }
        );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Knarf\UserBundle\Entity\Password\ResetPassword',
        ]);
    }
}
