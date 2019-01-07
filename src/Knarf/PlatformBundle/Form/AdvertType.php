<?php

namespace Knarf\PlatformBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('rubrique',   EntityType::class,  array('class' => 'KnarfPlatformBundle:Rubrique', 'choice_label' => 'intitule', 'placeholder' => 'Sélectionner la rubrique'))
                ->add('title',      TextType::class)
                //->add('content',    TextareaType::class)
                ->add('mediaFile',  VichFileType::class, array('required' => false))
                ->add('published',  CheckboxType::class, array('required' => false))
                ->add('enregistrer',       SubmitType::class)
                ->add('effacer',    ResetType::class)
                ->add('content',      CKEditorType::class, array('config_name' => 'full_config'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Knarf\PlatformBundle\Entity\Advert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'knarf_platformbundle_advert';
    }


}
