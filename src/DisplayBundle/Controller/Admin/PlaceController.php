<?php

namespace DisplayBundle\Controller\Admin;

use DisplayBundle\Entity\Place;
use DisplayBundle\Form\PlaceType;
use DisplayBundle\Uploader\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends Controller
{
    /**
     * @Route("/place", name="DisplayBundle_Place_list")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('DisplayBundle:Place');
        $places = $repository->findAll();

        return $this->render(
            '@Display/Admin/Place/list.html.twig',
            [
                'places' => $places,
            ]
        );
    }

    /**
     * @Route("/place/new", name="DisplayBundle_Place_new")
     */
    public function newAction(Request $request)
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($place);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Le lieu a été enregistré.');

                return $this->redirectToRoute('DisplayBundle_Place_list');
            }
        }

        return $this->render(
            '@Display/Admin/Place/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/place/{pk}/edit", name="DisplayBundle_Place_edit", requirements={"pk": "\d+"})
     */
    public function editAction(Request $request, $pk)
    {
        $repository = $this->getDoctrine()->getRepository('DisplayBundle:Place');
        $place = $repository->find($pk);

        if (null === $place) {
            throw new NotFoundHttpException('Le lieu avec l\'id '.$pk.' n\'existe pas');
        }

        $form = $this->createForm(PlaceType::class, $place);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($place);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Le lieu a été mit à jour.');

                return $this->redirectToRoute('DisplayBundle_Place_list');
            }
        }

        return $this->render(
            '@Display/Admin/Place/edit.html.twig',
            [
                'place' => $place,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/place/{pk}/delete", name="DisplayBundle_Place_delete", requirements={"pk": "\d+"})
     */
    public function deleteAction(Request $request, $pk)
    {
        $repository = $this->getDoctrine()->getRepository('DisplayBundle:Place');
        $place = $repository->find($pk);

        if (null === $place) {
            throw new NotFoundHttpException('Le lieu avec l\'id '.$pk.' n\'existe pas');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($place);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le lieu a été supprimé.');
        } catch (Exception $e) {
            $this->get('session')->setFlash('error', "Erreur lors de la suppression du lieu: ".$e->getMessage());
        }

        return $this->redirectToRoute('DisplayBundle_Place_list');
    }
}