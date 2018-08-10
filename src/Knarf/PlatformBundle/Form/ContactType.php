<?php

namespace Knarf\PlatformBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('nom',        TextType::class, array('constraints' => array(
                    new NotBlank(array('message' => 'Veuillez remplir ce champ'))
                        
                )))
                ->add('courriel',   EmailType::class, array('constraints' => array(
                    new NotBlank(array('message' => 'Veuillez remplir ce champ')),
                    new Assert\Email(array('message' => 'Veuillez donner une adresse email valide', 'checkMX' => true))
                )))
                ->add('nomFichier')
                ->add('objet',      TextType::class, array('constraints' => array(
                    new NotBlank(array('message' => 'Veuillez remplir ce champ'))
                )))
                ->add('message',    TextareaType::class, array('constraints' => array(
                    new NotBlank(array('message' => 'Veuillez remplir ce champ'))
                )))
                ->add('Envoyer',    SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Knarf\PlatformBundle\Entity\Contact'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'knarf_platformbundle_contact';
    }


}
