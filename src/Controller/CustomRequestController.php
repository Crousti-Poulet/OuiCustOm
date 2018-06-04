<?php

namespace App\Controller;

use App\Entity\CustomRequest;
use App\Form\CustomRequestAddForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomRequestController extends Controller
{

    /**
     * @Route("/customrequest/create", name="custom_request_create")
     */
    public function createCustomRequest(Request $request)
    {

        $customRequest = new CustomRequest();

        $form = $this->createForm(CustomRequestAddForm::class, $customRequest);


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $customRequest = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $customRequest->setUser($this->getUser());
            $em->persist($customRequest); // pour créer un nouvel enregistrement dans la base, seulement en création, pas en modif
            $em->flush(); // enregistre dans la base

            return $this->redirect($this->generateUrl('custom_request_create'));
        }
        return $this->render('custom_request/create.html.twig',[
            'form' => $form->createView(),
            'title' => 'Créer une demande de customisation'
        ]);
    }
}
