<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends Controller
{
    /**
     * @Route("/badge/demo/", name="badge_demo")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function demoAction()
    {
        return $this->render('demo.html.twig', []);
    }
}
