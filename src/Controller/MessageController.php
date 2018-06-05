<?php

namespace App\Controller;

use App\Entity\Conversation;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class MessageController extends Controller
{

    /**
     * @Route("/messaging", name="messagingPage")
     */
    public function messagingAction(Request $request)
    {
        $conversation_messages = $this->getDoctrine()->getManager()->getRepository(Conversation::class)->findAllByUser($this->getUser());

//        return $this->render('/default/messaging.html.twig', [
//            'conversation_messages' => $conversation_messages
//        ]);

        //Création du message
        $message = new Messaging();

        // Création et configuration du formulaire en utilisant sur createFormBuilder
        $form = $this->createFormBuilder($message)

            ->add('_sender', HiddenType::class, ['data'=> $this->getUser()->getUsername()])
            ->add('_message', TextareaType::class, ['label' => 'Votre message'])
            ->add('Envoyer', SubmitType::class)
            // Récupération
            ->getForm()
        ;

        //Analyse de la requête
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //Crée la date d'envoi
            $message->setSendingDate(new \Datetime());

            //Enregistrement dans la base de donnée
            $message = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->render('default/messaging.html.twig');
        }

        //Envoi au twig du resultat de la fonction createView ()
        return $this->render('default/messaging.html.twig',[
            'formMessage' => $form->createView()
        ]);

    }
}
