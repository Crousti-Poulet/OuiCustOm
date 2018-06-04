<?php

namespace App\Controller;

use App\Entity\CustomRequest;
use App\Form\CustomRequestAddForm;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomRequestController extends Controller
{
    /**
     * @Route("/customrequest/list", name="custom_request_list")
     */
    public function listCustomRequestsByUserLoggedIn()
    {
        $custom_requests = $this->getDoctrine()->getManager()->getRepository(CustomRequest::class)->findAllByUser($this->getUser());
        return $this->render('/custom_request/list.html.twig', [
            'custom_requests' => $custom_requests,
            'title' => 'Mes demandes de customisation'
        ]);
    }

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

            //upload de la photo de l'objet
            /** @var UploadedFile $file */
            $file = $customRequest->getPhotoPath();
            $fileName = $this->generateUniqueFileName().'.'. $file->guessExtension();
            //déplace le fichier image dans le dossier voulu
            $file->move($this->getParameter('customRequestPictures_directory'),$fileName);
            $customRequest->setPhotoPath($fileName);

            // lier la demande à l'utilisateur loggé
            $customRequest->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($customRequest); // pour créer un nouvel enregistrement dans la base, seulement en création, pas en modif
            $em->flush(); // enregistre dans la base

            return $this->redirect($this->generateUrl('custom_request_create'));
        }
        return $this->render('custom_request/create.html.twig',[
            'form' => $form->createView(),
            'title' => 'Créer une demande de customisation'
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
