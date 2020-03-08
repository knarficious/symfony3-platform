<?php

namespace Knarf\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Knarf\PlatformBundle\Form\MediaType;

class AdvertEditType extends AbstractType
{

  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('media',  MediaType::class, [
            'required' => false]);
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
