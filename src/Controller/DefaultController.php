<?php

namespace App\Controller;

use App\Entity\User;
<<<<<<< HEAD
use App\Entity\Messages;
use App\Entity\Image;
=======
use App\Entity\Message;
>>>>>>> e985b58bac865a007445a9b7a7b14fea434dc31a
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

<<<<<<< HEAD
    /**
     * @Route("/gallery", name="gallery")
     */
    public function galleryAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ARTISTE')) {
            return $this->redirectToRoute('errorUser');
        }
        $gallery = $this->getDoctrine()
            ->getRepository(Image::class)
            ->find($id);

        $userName = $gallery->getUser()->getUsername();
    }


=======
>>>>>>> e985b58bac865a007445a9b7a7b14fea434dc31a
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

            //Récupération de la date d'envoi du message
            $message->setReceiptDate(new \Datetime());

            //Enregistrement dans la base de donnée.
            $messageForAdmin = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($messageForAdmin);
            $em->flush();

            //Envoi de mail (ne fonctionne pas)
            $message = (new \Swift_Message($request->get('form')['_object']))
            ->setFrom($request->get('form')['_email'])
            ->setTo('admin@wanadoo.fr')
            ->setBody($request->get('form')['_message'])
            ->addPart('Expéditeur : ' . $request->get('form')['_author']); 
       
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
     * @Route("/ajax_handle", name="ajaxHandle")
     */
    public function ajaxHandle(Request $request)
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findByCategoryField('bananes');
        // return $this->render('default/home.html.twig');
        // dump($users);
        // die();
        return $this->json($users);
    }

}