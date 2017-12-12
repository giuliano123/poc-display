<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/", name="UserBundle_Index")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('UserBundle:Admin');
        $users = $repository->findAll();

        return $this->render(
            '@User/Admin/list.html.twig',
            [
                'users' => $users,
            ]
        );
    }
}