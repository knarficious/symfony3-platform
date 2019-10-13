<?php

namespace Knarf\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AdvertEditType extends AbstractType
{

  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('mediaFile',  VichFileType::class, array(
                                        'required' => false,
                                        'label' => true,
                                        'allow_delete' => true,
                                    'download_uri' => false));
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
