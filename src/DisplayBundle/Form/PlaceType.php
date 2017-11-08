<?php

namespace DisplayBundle\Form;

use DisplayBundle\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => false,
                'image_property' => 'imageFile',
                'download_link' => false
            ])
//            ->add('image', FileType::class, [
//                'data_class' => null,
//                'image_property' => 'imagePath',
//                'required' => false
//            ])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Place::class,
            ]
        );
    }
}