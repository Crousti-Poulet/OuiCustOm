<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
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
        // conversations de l'utilisateur
        $conversations = $this->getDoctrine()->getManager()->getRepository(Conversation::class)->findAllByUser($this->getUser());
//        if (count($conversations)>0)
//        {
//            $conversationId = $conversations[0]->getId();
//        }
//        else
//        {
//            $conversationId = 0;
//        }


        //Création du message
//        $message = new Message();

        // Création et configuration du formulaire en utilisant sur createFormBuilder
//        $form = $this->createFormBuilder($conversations)
//            ->add('_sender', HiddenType::class, ['data'=> $this->getUser()->getUsername()])
//            ->add('_message', TextareaType::class, ['label' => 'Votre message'])
//            ->add('Envoyer', SubmitType::class)
//            // Récupération
//            ->getForm()
//        ;

//        //Analyse de la requête
//        $form->handleRequest($request);

//        if($form->isSubmitted() && $form->isValid())
//        {
//            //Crée la date d'envoi
//            $message->setSendingDate(new \Datetime());
//
//            //Enregistrement dans la base de donnée
//            $message = $form->getData();
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($message);
//            $em->flush();
//
//            return $this->render('default/messaging.html.twig');
//        }

//        //Envoi au twig du resultat de la fonction createView ()
//        return $this->render('default/messaging.html.twig',[
//            'formMessage' => $form->createView()
//        ]);

        //Envoi au twig du resultat de la fonction createView ()
        return $this->render('default/messaging.html.twig',[
            'conversations' => $conversations
        ]);

//        // rediriger sur la messagerie avec la première conversation selectionnée
//        return $this->redirectToRoute('messagingPageConversation',['id' => $conversationId]);
    }

    /**
     * @Route("/messaging/{id}", name="messagingPageConversation")
     */
    public function messagingConversationSelected(Request $request,Conversation $conversation)
    {
        // conversations de l'utilisateur
        $conversations = $this->getDoctrine()->getManager()->getRepository(Conversation::class)->findAllByUser($this->getUser());

//        dump($conversations);
//        die();

        return $this->render('default/messaging.html.twig',[
            'conversations' => $conversations,
            'conversationSelected' => $conversation
        ]);

    }
}
