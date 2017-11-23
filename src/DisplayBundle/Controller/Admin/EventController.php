<?php

namespace DisplayBundle\Controller\Admin;

use DisplayBundle\Entity\Event;
use DisplayBundle\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends Controller
{
    /**
     * @Route("/", name="DisplayBundle_Event_list")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('DisplayBundle:Event');
        $events = $repository->findAll();

        return $this->render(
            '@Display/Admin/Event/list.html.twig',
            [
                'events' => $events,
            ]
        );
    }

    /**
     * @Route("/new", name="DisplayBundle_Event_new")
     */
    public function newAction(Request $request)
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Le spectacle a été enregistré.');

                return $this->redirectToRoute('DisplayBundle_Event_list');
            }
        }

        return $this->render(
            '@Display/Admin/Event/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{pk}/edit", name="DisplayBundle_Event_edit", requirements={"pk": "\d+"})
     */
    public function editAction(Request $request, $pk)
    {
        $repository = $this->getDoctrine()->getRepository('DisplayBundle:Event');
        $event = $repository->find($pk);

        if (null === $event) {
            throw new NotFoundHttpException('Le spectacle d\'id '.$pk.' n\'existe pas');
        }

        $form = $this->createForm(EventType::class, $event);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Le spectacle a été mit à jour.');

                return $this->redirectToRoute('DisplayBundle_Event_list');
            }
        }

        return $this->render(
            '@Display/Admin/Event/edit.html.twig',
            [
                'event' => $event,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{pk}/delete", name="DisplayBundle_Event_delete", requirements={"pk": "\d+"})
     */
    public function deleteAction(Request $request, $pk)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('DisplayBundle:Event');
        $event = $repository->find($pk);

        if (null === $event) {
            throw new NotFoundHttpException('Le spectacle d\'id '.$pk.' n\'existe pas');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le spectacle a été supprimé.');
        } catch (Exception $e) {
            $this->get('session')->setFlash('error', 'Erreur lors de la suppression du spectacle: '.$e->getMessage());
        }

        return $this->redirectToRoute('DisplayBundle_Event_list');
    }
}