<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Webmozart\Assert\Assert;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
     * @Route("/help", name="helpPage")
     */
    public function helpAction(Request $request)
    {
        return $this->render('default/help.html.twig');
    }

    /**
     * @Route("/registration", name="registrationPage")
     * 
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
                 $user->setCreatedAt(new \Datetime());
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
}