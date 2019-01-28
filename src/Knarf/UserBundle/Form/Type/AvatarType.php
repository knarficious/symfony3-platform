<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Description of EditAvatarType
 *
 * @author franck
 */
class AvatarType extends AbstractType 
{
    
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $builder->add('mediaFile', VichImageType::class, array(
                'required' => true,
                'download_link' => false,
                'allow_delete' => false))        
                ->add('register', SubmitType::class, array(
            'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
            'label' => 'user.registration.register'
        ));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Knarf\UserBundle\Entity\Avatar',
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
