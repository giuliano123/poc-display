<?php

namespace DisplayBundle\Form;

use DisplayBundle\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('publicationDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label'    => 'Date et heure de publication',
                'attr' => [
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd/mm/yyyy',
                    'placeholder' => 'JJ/MM/AAAA',
                ]
            ])
            ->add('publicationEndDate', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label'    => 'Date et heure de fin de publication',
                'attr' => [
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd/mm/yyyy',
                    'placeholder' => 'JJ/MM/AAAA',
                ]
            ])
            ->add('title', TextType::class)
            ->add('subtitle', TextType::class)
            ->add('eventDate', TextType::class)
            ->add('place', EntityType::class, [
                'class' => 'DisplayBundle\Entity\Place',
                'choice_label' => 'title'
            ])
            ->add('poster', FileType::class, [
                'data_class' => null,
                'image_property' => 'posterPath',
                'required' => false
            ])
            ->add('picture', FileType::class, [
                'data_class' => null,
                'image_property' => 'picturePath',
                'required' => false
            ])
            ->add('save', SubmitType::class)
            ->getForm();

        $builder->get('publicationEndDate')->addModelTransformer(new CallbackTransformer(
            function ($date) {
                /* @var \DateTime $date */

                return $date->format('Y-m-d H:i:s') === Event::MAX_PUBLICATION_DATE_END ? null : $date;
            },
            function ($date) {
                return null === $date ? new \DateTime(Event::MAX_PUBLICATION_DATE_END) : $date;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Event::class
        ));
    }
}