<?php

namespace App\Controller;

use App\Entity\CustomRequest;
use App\Entity\Message;
use App\Entity\User;
use App\Entity\Conversation;
use App\Form\MessageForm;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class MessageController extends Controller
{
    /**
     * @Route("/contact/customRequest/{id}", name="contactUserCustomRequest")
     * Envoyer un nouveau message à un utilisateur concernant sa demande
     */
    public function contactCustomRequest(Request $request, CustomRequest $customRequest)
    {
        $conversation = new Conversation();
        $message = new Message();

        $form = $this->createForm(MessageForm::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // ajouter l'utilisateur connecté et l'auteur de la demande à la conversation
            $conversation->addUser($this->getUser());
            $conversation->addUser($customRequest->getUser());
            $conversation->setCustomRequest($customRequest);
            $conversation->setCreationDate(new \DateTime("now"));

            // compléter l'entité Message avec toutes les infos
            $message->setCreationDate(new \DateTime("now"));
            $message->setConversation($conversation);
            $message->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirect($this->generateUrl('messagingPageConversation', ['id' => $conversation->getId()]));

        }

        return $this->render('default/newMessage.html.twig', [
            'formMessage' => $form->createView(),
            'recipient' => $customRequest->getUser()
        ]);

    }

    /**
     * @Route("/contact/artist/{id}", name="contactArtist")
     * Envoyer un nouveau message à un artiste
     */
    public function contactArtist(Request $request, User $artist)
    {
        $conversation = new Conversation();
        $message = new Message();

        $form = $this->createForm(MessageForm::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // ajouter l'utilisateur connecté et l'artiste à la conversation
            $conversation->addUser($artist);
            $conversation->addUser($this->getUser());
            $conversation->setCreationDate(new \DateTime("now"));

            // compléter l'entité Message avec toutes les infos
            $message->setCreationDate(new \DateTime("now"));
            $message->setConversation($conversation);
            $message->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirect($this->generateUrl('messagingPageConversation',['id' => $conversation->getId()]));

        }

        return $this->render('default/newMessage.html.twig', [
            'formMessage' => $form->createView(),
            'recipient' => $artist
        ]);
    }

    /**
     * @Route("/messaging", name="messagingPage")
     * @Route("/messaging/{id}", name="messagingPageConversation")
     * @Route("/messaging/addMessage/{id}", name="messagingSendMessage")
     * Envoyer un message dans une conversation déjà existante
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

            return $this->redirect($this->generateUrl('messagingPageConversation',['id' => $conversation->getId()]));
        }

        return $this->render('default/messaging.html.twig', [
            'formMessage' => $form->createView(),
            'conversations' => $conversations,
            'conversationSelected' => $conversation
        ]);
    }


}
