<?php

namespace Knarf\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdvertEditType extends AbstractType
{

  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('date');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'knarf_platformbundle_advert_edit';
    }
    
    
    public function getParent()

    {

        return AdvertType::class;

    }
    



}
