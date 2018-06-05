<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Messaging;
use App\Entity\AdminContact;
use App\Entity\Conversation;
use Webmozart\Assert\Assert;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homePage") 
     */
    public function homeAction(Request $request)
    {
        return $this->render('default/home.html.twig');
    }

    //pages d'erreurs
    /**
     * @route("/error/errorUser", name="errorUser")
     */

    public function errorUserAction(Request $request)
    {
        return $this->render('error/errorUser.html.twig');
    }
    //fin des pages d'erreurs
    /**
     * @Route("/default/artistview", name="artistview")
     */
    public function artistviewAction (Request $request)
    {

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ARTISTE')){
            return $this->redirectToRoute('errorUser');
        }
        return $this->render('default/artistview.html.twig');
    }


    /**
     * @Route("/default/userview", name="userview")
     */
    public function userviewAction (Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->redirectToRoute('errorUser');
        }
        return $this->render('default/userview.html.twig');
    }

    /**
     * @Route("/default/profildetail", name="profildetail")
     *@Security("has_role('ROLE_USER', 'ROLE_ARTISTE')")
     */
    public function profildetailAction (Request $request)
    {
        return $this->render('default/profildetail.html.twig');
    }
    //page du détqail de profil d'un artiste
    /**
     * @Route("/default/profildetail/{id}", name="profildetailuser")
     */
    public function profildetailUserAction (Request $request, User $user)
    {
        if(!in_array('ROLE_ARTISTE', $user->getRoles())){//on vérrifie que l'id est bien celle d'un artiste
            return $this->redirectToRoute('errorUser');// si ce n'est pas le cas, on retourne un 404
        }
        return $this->render('default/profildetail.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/contact", name="contactPage")
     */
    public function contactAction(Request $request, \Swift_Mailer $mailer)
    {
        //Création du message
        $message = new AdminContact ();       
        
        // Création et configuration du formulaire en se utilisant sur createFormBuilder 
        $form = $this->createFormBuilder($message) 
                     ->add('_author', TextType::class, ['label' => 'Nom'])     
                     ->add('_email', EmailType::class, ['label' => 'E-mail'])   
                     ->add('_object', TextType::class, ['label' => 'Objet'])
                     ->add('_message', TextareaType::class, ['label' => 'Votre message'])
                     ->add('Envoyer', SubmitType::class)
                     // Récupération
                     ->getForm() 
                     ;

        //Analyse de la requête
        $form->handleRequest($request);
             
        if($form->isSubmitted() && $form->isValid()) {

            //Enregistrement dans la base de donnée.
            $messageForAdmin = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($messageForAdmin);
            $em->flush();
            
            //Envoi de mail (ne fonctionne pas)
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('el-ouni-mehdi@hotmail.fr')
            ->setBody('Coucou !');
       
            $mailer->send($message);
            return $this->redirect($this->generateUrl('contactPage'));

        }

        //Envoi au twig du resultat de la fonction createView ()
        return $this->render('default/contact.html.twig',[
            'formMessage' => $form->createView()
        ]);
    }

    /**
     * @Route("/help", name="helpPage")
     */
    public function helpAction(Request $request)
    {
        return $this->render('default/help.html.twig');
    }

    
    /**
     * @Route("/messaging", name="messagingPage")
     */
    public function messagingAction(Request $request)
    {
        $conversation_messages = $this->getDoctrine()->getManager()->getRepository(Conversation::class)->findAllByUser($this->getUser());
        return $this->render('/default/messaging.html.twig', [
            'conversation_messages' => $conversation_messages
        ]);

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
             
        if($form->isSubmitted() && $form->isValid()) {

            
            //Crée la date d'envoi
            $message->setSendingDate(new \Datetime());

            //Enregistrement dans la base de donnée.
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