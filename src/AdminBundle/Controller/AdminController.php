<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/", name="AdminBundle_Index")
     */
    public function indexAction()
    {
        return $this->render(
            '@Admin/base.html.twig'
        );
    }
}