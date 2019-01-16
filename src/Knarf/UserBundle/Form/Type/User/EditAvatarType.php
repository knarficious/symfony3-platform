<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Form\Type\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Knarf\UserBundle\Entity\Manager\Interfaces\UserManagerInterface;



/**
 * Description of EditAvatarType
 *
 * @author franck
 */
class EditAvatarType extends AbstractType 
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
        $builder->add('mediaFile', VichFileType::class, array(
                'required' => false,
                'allow_delete' => true,
                'download_link' => true));
        
        $builder->add('register', SubmitType::class, array(
            'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
            'label' => 'user.registration.register'
        ));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Knarf\UserBundle\Entity\User\Avatar',
        ]);
    }
    
        /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'knarf_userbundle_avatar';
    }
}
