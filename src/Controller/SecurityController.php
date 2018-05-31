<?php

namespace App\Controller;

use App\Entity\User;
use Webmozart\Assert\Assert;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{



//INSCRIPTION

    /**
     * @Route("/registration", name="registrationPage")
     * 
     */

    public function registration(Request $request, ObjectManager $manager)
    {
         $user = new User (); // on crée l'utilisateur       
        $form = $this->createFormBuilder($user) // on CREE et CONFIGURE le form grace a createFormBuilder qui sera lié a $user 
                     ->add('username',TextType::class,[
                        'required'    => true,
                        'constraints' => [
                            new Length( [
                                'min' => 3,
                                'max' => 20,
                                'minMessage' => "Minimum 3 caractères",
                                'maxMessage' => "Maximum 20 caractères"
                            ]),
                            new NotBlank( [
                                'message' => "Champs non-rempli" 
                            ])                                           
                        ] ,            
                    ]) 
                     ->add('location',TextType::class,[
                        'required'    => true,
                        'constraints' => [
                            new Length( [
                                'min' => 3,
                                'max' => 20,
                                'minMessage' => "Minimum 3 caractères",
                                'maxMessage' => "Maximum 20 caractères"
                            ]),
                            new NotBlank( [
                                'message' => "Champs non-rempli" 
                            ])                                           
                        ] ,            
                    ])     
                     ->add('email', EmailType::class,[
                        'required'    => true,
                        'constraints' => [
                            new Email( [
                                'message' => "Format non-valide (monadress@exemple.com)"
                            ]),
                            new NotBlank( [
                                'message' => "Champs non-rempli" 
                            ])                     
                        ] ,            
                    ])   
                     ->add('role', ChoiceType::class,[
                          'expanded' => true,
                          'multiple' => true,
                          'label'     =>false,
                          'choices' => [
                              'S\'inscrire en tant qu\'Artiste'     => "ROLE_ARTISTE"
                            ]
                     ])  
                     ->add('plainPassword', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'first_options' => ['label' => 'Entrez le mot de passe'],
                        'second_options' => ['label' => 'Entrez à nouveau le mot de passe'],
                        'required'    => true,
                        'constraints' => [
                            new Length( [
                                'min' => 8,
                                'max' => 50,
                                'minMessage' => "Minimum 3 caractères",
                                'maxMessage' => "Maximum 50 caractères"
                            ]),
                            new NotBlank( [
                                'message' => "Champs non-rempli" 
                            ])                                           
                        ] 
                    ])
                                            
                     ->getForm() ;       // on le RECUPERE   

        $form->handleRequest($request);  // ANALYSE de la requete et ducou symfony lié title content avec $user
         
        //Verification de la soumission du formulaire
        if($form->isSubmitted() && $form->isValid()) {

            if(!$user->getId()){
                 $user->setCreatedAt(new \Datetime());
            }

             if(!$user->getRole()){
                 $user->setRole(['ROLE_USER']);
            }
            
            $user = $form->getData(); // getData() garde les valeurs soumises au formulaire
 
            $manager->persist($user); //on demande au manager de se preparer a faire persister l'article
            $manager->flush();           //on demande au manager de lancer la requete

            return $this->redirectToRoute('homepage');
        }

       
        return $this->render('security/registration.html.twig',[
            'formUser' => $form->createView(), //on envoi a twig le RESULTAT de la fonction createView () == cree un petit objet plutot type affichage.
        ]);
    }


    //////////////////////////////
}