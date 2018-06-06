<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Entity\Conversation;
use App\Form\MessageForm;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class MessageController extends Controller
{

//    /**
//     * @Route("/messaging", name="messagingPage")
//     */
//    public function messagingAction(Request $request)
//    {
//        // conversations de l'utilisateur
//        $conversations = $this->getDoctrine()->getManager()->getRepository(Conversation::class)->findAllByUser($this->getUser());
//
//        // conversation sélectionnée = la première s'il y en a une
//        if (count($conversations)>0)
//        {
//            $conversation = $conversations[0];
//        }
//        else
//        {
//            $conversation = null;
//        }
//
//        $message = new Message();
//        $form = $this->createForm(MessageForm::class, $message);
//
//        //Envoi au twig du resultat de la fonction createView ()
//        return $this->render('default/messaging.html.twig', [
//            'formMessage' => $form->createView(),
//            'conversations' => $conversations,
//            'conversationSelected' => $conversation
//        ]);
//
////        // rediriger sur la messagerie avec la première conversation selectionnée
////        return $this->redirectToRoute('messagingPageConversation',['id' => $conversationId]);
//    }

//    /**
//     * @Route("/messaging/{id}", name="messagingPageConversation")
//     */
//    public function messagingConversationSelected(Request $request,Conversation $conversation)
//    {
//        // conversations de l'utilisateur
//        $conversations = $this->getDoctrine()->getManager()->getRepository(Conversation::class)->findAllByUser($this->getUser());
//
//        return $this->render('default/messaging.html.twig',[
//            'conversations' => $conversations,
//            'conversationSelected' => $conversation
//        ]);
//    }

    /**
     * @Route("/messaging", name="messagingPage")
     * @Route("/messaging/{id}", name="messagingPageConversation")
     * @Route("/messaging/addMessage/{id}", name="messagingSendMessage")
     */
    public function sendMessage(Request $request, Conversation $conversation = null)
    {
        // conversations de l'utilisateur
        $conversations = $this->getDoctrine()->getManager()->getRepository(Conversation::class)->findAllByUser($this->getUser());

        // si pas de conversation en paramètre (<=> route /messaging)
        if ($conversation == null) {
            // sélectionner la première conversation  s'il y en a une
            if (count($conversations) > 0) {
                $conversation = $conversations[0];
            } else {
                $conversation = null;
            }
        }

        $message = new Message();
        $form = $this->createForm(MessageForm::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // compléter l'entité Message avec toutes les infos
            $message->setCreationDate(new \DateTime("now"));
            $message->setConversation($conversation);
            $message->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
//            return $this->redirect($this->generateUrl('messagingSendMessage'));
        }

//        return $this->redirect($this->generateUrl('messagingPageConversation', ['id' => $conversation->getId()]));

        // dans tous les cas on reste sur la page de messagerie
        return $this->render('default/messaging.html.twig', [
            'formMessage' => $form->createView(),
            'conversations' => $conversations,
            'conversationSelected' => $conversation
        ]);
    }
}
