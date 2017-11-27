<?php

namespace DisplayBundle\Form;

use DisplayBundle\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
            ->getForm();

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Place $place */
                $place = $event->getData();
                $form = $event->getForm();

                $form->add(
                    'imageFile',
                    VichFileType::class,
                    [
                        'label' => 'Image d\'illustration',
                        'required' => $place->getId() ? false : true,
                        'allow_delete' => false,
                        'image_property' => 'imageFile',
                        'download_link' => false,
                        'preset' => '359x96'
                    ]
                );
            }
        );
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