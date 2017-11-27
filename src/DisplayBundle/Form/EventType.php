<?php

namespace DisplayBundle\Form;

use DisplayBundle\Entity\Event;
use DisplayBundle\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'publicationDate',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'label' => 'Date et heure de publication',
                    'attr' => [
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd/mm/yyyy',
                        'placeholder' => 'JJ/MM/AAAA',
                    ],
                ]
            )
            ->add(
                'publicationEndDate',
                DateTimeType::class,
                [
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'label' => 'Date et heure de fin de publication',
                    'attr' => [
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd/mm/yyyy',
                        'placeholder' => 'JJ/MM/AAAA',
                    ],
                ]
            )
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('subtitle', TextType::class, ['label' => 'Sous-titre', 'required' => false])
            ->add('eventDate', TextType::class, ['label' => 'Date du spectacle', 'required' => false])
            ->add(
                'place',
                EntityType::class,
                [
                    'class' => Place::class,
                    'choice_label' => 'title',
                    'label' => 'Lieu',
                ]
            )
            ->getForm();

        $builder->get('publicationEndDate')->addModelTransformer(
            new CallbackTransformer(
                function ($date) {
                    /* @var \DateTime $date */

                    return $date->format('Y-m-d H:i:s') === Event::MAX_PUBLICATION_DATE_END ? null : $date;
                },
                function ($date) {
                    return null === $date ? new \DateTime(Event::MAX_PUBLICATION_DATE_END) : $date;
                }
            )
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Event $myEvent */
                $myEvent = $event->getData();
                $form = $event->getForm();

                $form->add(
                    'pictureFile',
                    VichFileType::class,
                    [
                        'label' => 'Illustration',
                        'required' => $myEvent->getId() ? false : true,
                        'allow_delete' => false,
                        'image_property' => 'pictureFile',
                        'download_link' => false,
                        'preset' => '114x150'
                    ]
                )
                    ->add(
                        'posterFile',
                        VichFileType::class,
                        [
                            'label' => 'Affiche',
                            'required' => $myEvent->getId() ? false : true,
                            'allow_delete' => false,
                            'image_property' => 'posterFile',
                            'download_link' => false,
                            'preset' => '150x100'
                        ]
                    );
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Event::class,
            )
        );
    }
}