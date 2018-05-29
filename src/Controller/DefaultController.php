<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/home", name="homepage")
     */
    public function homeAction(Request $request)
    {
        return $this->render('default/home.html.twig');
    }
}