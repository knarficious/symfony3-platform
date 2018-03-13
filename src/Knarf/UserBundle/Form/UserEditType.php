<?php

namespace Knarf\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserEditType extends AbstractType
{

  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('mediaFile', VichFileType::class, array('required' => FALSE))
                ->remove('username')
                ->remove('plainPassword')
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'knarf_userbundle_user_edit';
    }
    
    
    public function getParent()

    {

        return UserType::class;

    }
    



}
