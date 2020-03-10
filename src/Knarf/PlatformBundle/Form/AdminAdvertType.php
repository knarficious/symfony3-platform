<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Knarf\PlatformBundle\Form\MediaType;

/**
 * Description of AdminAdvertType
 *
 * @author franck
 */
class AdminAdvertType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        parent::buildForm($builder, $options);
        $builder->remove('media', MediaType::class);
        $builder->add('isAdmin', CheckboxType::class, array(
                                        'required' => true,
                                        'label' => 'Article administratif '));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        parent::getBlockPrefix();
    }
    
    
    public function getParent()

    {

        return AdvertType::class;

    }
}
