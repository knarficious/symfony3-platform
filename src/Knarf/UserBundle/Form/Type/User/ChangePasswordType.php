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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Knarf\UserBundle\Entity\Password\ChangePassword;
use Knarf\UserBundle\Entity\Manager\Interfaces\UserManagerInterface;

/**
 * Description of ChangePasswordType
 *
 * @author franck
 */
class ChangePasswordType extends AbstractType 
{
   /**
     *
     * @var UserManagerInterface $handler
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
        $builder
            ->add('oldPassword', PasswordType::class, ['label' => 'user.change_password.old_password' ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'user.change_password.new_password',
                ],
                'second_options' => [
                    'label' => 'user.change_password.repeat_new_password',
                ]
            ])
        ;
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                if (!$data instanceof ChangePassword) {
                    throw new \RuntimeException('ChangePassword instance required.');
                }
                $oldPassword = $data->getOldPassword();
                $newPassword = $data->getNewPassword();
                $form = $event->getForm();
                if (!$oldPassword || !$newPassword || count($form->getErrors(true))) {
                    return;
                }
    
                $user = $data->getUser();
    
                if (!$this->userManager->isPasswordValid($user, $oldPassword)) {
                    $form = $event->getForm();
                    $form->get('oldPassword')->addError(new FormError('Previous password is not valid.'));
                    return;
                }
            }
        );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Knarf\UserBundle\Entity\Password\ChangePassword',
        ]);
    } 
}
