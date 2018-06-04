<?php

namespace App\Controller;

use App\Entity\Messaging;
use App\Entity\User;
use App\Entity\AdminContact;
use Webmozart\Assert\Assert;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/home", name="homePage")
     */
    public function homeAction(Request $request)
    {
        return $this->render('default/home.html.twig');
    }

    /**
     * @Route("/default/artistview", name="artistview")
     * @Security("has_role('ROLE_USER')")
     */
    public function artistviewAction (Request $request)
    {
        return $this->render('default/artistview.html.twig');
    }

    /**
     * @Route("/default/profildetail", name="profildetail")
     */
    public function profildetailAction (Request $request)
    {
        return $this->render('default/profildetail.html.twig');
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
            // $message = (new \Swift_Message('Hello Email'))
            // ->setFrom('send@example.com')
            // ->setTo('el-ouni-mehdi@hotmail.fr')
            // ->setBody('Coucou !'
        // );
       
            // $mailer->send($message);
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
     * @Route("/registration", name="registrationPage")
     */
    public function registration(Request $request, ObjectManager $manager)
    {
       
         $user = new User (); // on crée l'utilisateur       
        
        $form = $this->createFormBuilder($user) // on CREE et CONFIGURE le form grace a createFormBuilder qui sera lié a $user 
                     ->add('username')     
                     ->add('email')   
                     ->add('password')     
                    //  ->add('confirm_password')     
                     ->add('location')     
                     ->add('role', ChoiceType::class,[
                          'expanded' => true,
                          'multiple' => true,
                          'label'     =>false,
                          'choices' => [ 
                              'S\'inscrire en tant qu\'Artiste'     => "ROLE_ARTISTE"
                            ]
                     ])     
                     
                     ->getForm() ;       // on le RECUPERE   

        $form->handleRequest($request);  // ANALYSE de la requete et ducou symfony lié title content avec $article
             
        if($form->isSubmitted() && $form->isValid()) {

            if(!$user->getId()){
                 $user->setCreationDate(new \Datetime());
            }

             if(!$user->getRole()){
                 $user->setRole(['ROLE_USER']);
            }

       
            $manager->persist($user); //on demande au manager de se preparer a faire persister l'article
            $manager->flush();           //on demande au manager de lancer la requete

            return $this->redirectToRoute('homepage');
        }

       
        return $this->render('default/registration.html.twig',[
            'formUser' => $form->createView(), //on envoi a twig le RESULTAT de la fonction createView () == cree un petit objet plutot type affichage.
        ]);
    }
    
    /**
     * @Route("/messaging", name="messagingPage")
     */
    public function messagingAction(Request $request)
    {
        //Création du message
        $message = new Messaging();       
        
        // Création et configuration du formulaire en se utilisant sur createFormBuilder 
        $form = $this->createFormBuilder($message) 
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