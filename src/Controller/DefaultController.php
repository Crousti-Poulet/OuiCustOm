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
        // Todo: 



        return $this->render('default/home.html.twig');
    }

    /**
     * @Route("/registration", name="registrationPage")
     */

    public function registration()
    {
   


        return $this->render('default/registration.html.twig');
    }
}