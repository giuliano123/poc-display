<?php

namespace AdminBundle\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class VichyTypeExtension extends AbstractTypeExtension
{

    private $helper;

    public function __construct(UploaderHelper $helper)
    {
        $this->helper = $helper;
    }
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return VichFileType::class;
    }

    /**
     * Add the image_path option
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('image_property'));
        $resolver->setDefined(array('preset'));
    }

    /**
     * Pass the image url to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['image_property'])) {

            $parentData = $form->getParent()->getData();

            $imageUrl = null;
            if (null !== $parentData) {
                $imageUrl = $this->helper->asset($parentData, $options['image_property']);
            }

            $view->vars['image_url'] = $imageUrl;
        }

        if (isset($options['preset'])) {
            list($width, $height) = explode('x', $options['preset']);
            $view->vars['image_width'] = $width;
            $view->vars['image_height'] = $height;
        }
    }
}