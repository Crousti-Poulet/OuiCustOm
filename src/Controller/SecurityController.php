<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginForm;
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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends Controller
{

////////INSCRIPTION////////

    /**
     * @Route("/registration", name="registrationPage")
     * 
     */
    public function registration(Request $request, ObjectManager $manager)
    {
        $user = new User (); // on crée l'utilisateur
        $user->setProfilPicture('placeholder_profil.png'); //Met un placeholder à la place de la photo de profil       
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
                                'message' => "Le pseudo est obligatoire"
                            ])                                           
                        ] ,            
                    ]) 
                     ->add('city',TextType::class,[
                        'required'    => true,
                        'constraints' => [
                            new Length( [
                                'min' => 2,
                                'max' => 50,
                                'minMessage' => "Minimum 2 caractères",
                                'maxMessage' => "Maximum 50 caractères"
                            ]),
                            new NotBlank( [
                                'message' => "La ville est obligatoire"
                            ])                                           
                        ] ,            
                    ])
                    ->add('zipcode',TextType::class,[
                        'required'    => true,
                        'constraints' => [
                            new Length( [
                                'min' => 5,
                                'max' => 5,
                                'minMessage' => "Minimum 5 caractères",
                                'maxMessage' => "Maximum 5 caractères"
                            ]),
                            new NotBlank( [
                                'message' => "Le code postal est obligatoire"
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
                                'message' => "L'adresse email est obligatoire"
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
                                'minMessage' => "Minimum 8 caractères",
                                'maxMessage' => "Maximum 50 caractères"
                            ]),
                            new NotBlank( [
                                'message' => "Le mot de passe est obligatoire"
                            ])                                           
                        ] 
                    ])
                                            
                     ->getForm() ;       // on le RECUPERE   

        $form->handleRequest($request);  // ANALYSE de la requete et ducou symfony lié title content avec $user
         
        //Verification de la soumission du formulaire
        if($form->isSubmitted() && $form->isValid()) {

            if(!$user->getId()){
                 $user->setCreationDate(new \Datetime());
            }

             if(!$user->getRole()){
                 $user->setRole(['ROLE_USER']);
            }
            
            $user = $form->getData(); // getData() garde les valeurs soumises au formulaire
 
            $manager->persist($user); //on demande au manager de se preparer a faire persister l'article
            $manager->flush();           //on demande au manager de lancer la requete
            $this->addFlash('success', 'Votre compte à bien été créé.');
            return $this->redirectToRoute('homePage');
        }

        return $this->render('security/registration.html.twig',[
            'formUser' => $form->createView(), //on envoi a twig le RESULTAT de la fonction createView () == cree un petit objet plutot type affichage.
        ]);
    }


    //////// LOGIN ///////////

   /**
     * @Route("/login", name="security_login")
     */
    public function loginAction(AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        return $this->render('security/loginForm.html.twig', [
            'monForm'      => $form->createView(),
            'error'     => $error,
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('this should not be reached!');
    }

   //////// MDP OUBLIE /////////// 
    /**
     * @Route("/reset", name="reset_password")
     *
     */
    public function resetPass(Request $request, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator, ObjectManager $manager)
    {
               
        
        // Création et configuration du formulaire en se utilisant sur createFormBuilder 
        $form = $this->createFormBuilder() 
                       
                     ->add('_email', EmailType::class, ['label' => 'E-mail'])   
                     
                    //  ->add('Envoyer', SubmitType::class)
                     // Récupération
                     ->getForm() 
                     ;

        //Analyse de la requête
        $form->handleRequest($request);
             
        if($form->isSubmitted() && $form->isValid()) {

            //Récupération de la date d'envoi du message
            $token = $tokenGenerator->generateToken(); //generation du token
            $user = $manager->getRepository(User::class)->findOneBy([
                'email' => $request->get('form')['_email']
                ]);
            //enregistrement dans la base de donnée.
            if($user){
  
                $user->setTokenpassword($token);
                $em = $this->getDoctrine()->getManager();
                $em->flush();

            
                //Envoi de mail (ne fonctionne pas)
                $message = (new \Swift_Message('Recuperation de mot de passe'))
                ->setFrom('admin@wanadoo.fr')
                ->setTo($request->get('form')['_email'])
                ->setBody('cliquez sur le liens suivants pour /recuperer & modifier votre mot de passe : <a href="http://localhost:8000/resetCheck/'. $token.'">' )
                ->addPart('Expéditeur : OuiCustOm Team ' ); 
        
                $mailer->send($message);
             }
             return $this->redirect($this->generateUrl('security_login'));

        }

        //Envoi au twig du resultat de la fonction createView ()
        return $this->render('security/resetPass.html.twig',[
            'formReset' => $form->createView()
        ]);
    }


    /**
     * @Route("/resetCheck/{token}", name="reset_check")
     *
     */
      public function resetCheck(Request $request, ObjectManager $manager, $token){
        $user = $manager->getRepository(User::class)->findOneBy([
            'tokenpassword' => $token
            ]);
        if($user){
            $form = $this->createFormBuilder($user) 
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Entrez le nouveau mot de passe'],
                    'second_options' => ['label' => 'Confirmer le nouveau de mot de passe'],
                    'required'    => true,
                    'constraints' => [
                        new Length( [
                            'min' => 8,
                            'max' => 50,
                            'minMessage' => "Minimum 8 caractères",
                            'maxMessage' => "Maximum 50 caractères"
                        ]),
                        new NotBlank( [
                            'message' => "Le mot de passe est obligatoire"
                        ])                                          
                    ]
                ])    
                    
                     ->getForm() ;

            //Analyse de la requête
            $form->handleRequest($request);
                
            //Verification de la soumission du formulaire
            if($form->isSubmitted() && $form->isValid()) {
            
                $user = $form->getData(); // getData() garde les valeurs soumises au formulaire
    
                //$manager->persist($user); //on demande au manager de se preparer a faire persister l'article
                $manager->flush();           //on demande au manager de lancer la requete
                $this->addFlash('success', 'Votre compte à bien été créé.');
                return $this->redirectToRoute('homePage');
            }
        }else{
            throw $this->createNotFoundException('Error 404');
        }
    
        return $this->render('security/modifPass.html.twig',[
            'formNew' => $form->createView(), //on envoi a twig le RESULTAT de la fonction createView () == cree un petit objet plutot type affichage.
        ]);
    }  
}    
