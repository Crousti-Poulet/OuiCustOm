<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Message;
use App\Entity\Messages;
use App\Entity\Messaging;
use App\Entity\AdminContact;
use App\Entity\Conversation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Webmozart\Assert\Assert;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homePage") 
     */
    public function homeAction(Request $request)
    {
        // uniquement pour faire le champ dropdown à partir de la BD
        $form = $this->createFormBuilder()
            ->add('category_id', EntityType::class, [
                'class' => 'App\Entity\Category',
                'choice_label' => 'name',
                'placeholder' => '-- Catégorie --',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => false
            ])
            // Récupération
            ->getForm()
        ;

        //Analyse de la requête
        $form->handleRequest($request);

        return $this->render('default/home.html.twig',[
            'formSearch' => $form->createView()
        ]);
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
    

    // Affiche le Dashboard de l'artiste connecté.
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

            // si aucun champ de recherche n'est rempli, tous les artisans sont affichés
            // la requête SQL est construite en fonction des champs renseignés
            $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAllByText($request->request->get('search'), $request->request->get('form')['category_id'], $request->request->get('city'), $request->request->get('zipcode'));


            $encoder = new JsonEncoder();
            $normalizer = new ObjectNormalizer();
            $normalizer->setIgnoredAttributes(array('category','customRequestsCreated','customRequestsAssigned','conversations','images','messages'));
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();

            });
//        }

//        //Si les champs catégorie, ville et code postal sont remplis
//        elseif(null!==($request->get('category_id')) && ''!==($request->get('category_id'))  && null!==($request->get('city'))
//        && ''!==($request->get('city')) && null!==($request->get('zipcode')) && ''!==($request->get('zipcode'))) {
//
//            $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAllByCategoryAndCityAndZipcode($request->get('category_id'), $request->get('city'), $request->get('zipcode'));
//
//            $encoder = new JsonEncoder();
//            $normalizer = new ObjectNormalizer();
//
//            $normalizer->setCircularReferenceHandler(function ($object) {
//                return $object->getId();
//
//            });
//        }
//        //Si seuls les champs catégorie et code postal ou seuls les champs catégorie et ville sont remplis
//        elseif((null!==$request->get('category_id') && ''!==$request->get('category_id')  && null!==$request->get('city') && ''!==$request->get('city') )
//        || (null!==$request->get('category_id') && ''!==$request->get('category_id')  && null!==$request->get('zipcode') && ''!==$request->get('zipcode') )
//        || (null!==($request->get('category_id')) && ''!==($request->get('category_id')))) {
//
//            //Si seuls les champs catégorie et code postal sont remplis
//            if(null!==$request->get('category_id') && ''!==$request->get('category_id') && null!==$request->get('zipcode') && ''!==$request->get('zipcode')) {
//
//                $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAllByCategoryAndZipcode($request->get('category_id'), $request->get('zipcode'));
//
//                $encoder = new JsonEncoder();
//                $normalizer = new ObjectNormalizer();
//
//                $normalizer->setCircularReferenceHandler(function ($object) {
//                    return $object->getId();
//
//            });
//
//            }
//            //Si seuls les champs catégorie et ville sont remplis
//            elseif (null!==($request->get('category_id')) && ''!==($request->get('category_id')) && null!==($request->get('city')) && ''!==($request->get('city'))) {
//
//                $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAllByCategoryAndCity($request->get('category_id'), $request->get('city'));
//
//                $encoder = new JsonEncoder();
//                $normalizer = new ObjectNormalizer();
//
//                $normalizer->setCircularReferenceHandler(function ($object) {
//                    return $object->getId();
//
//                });
//            }
//            //Si seul le champ catégorie est rempli
//            elseif (null!==($request->get('category_id')) && ''!==($request->get('category_id'))) {
//
//            $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAllByCategory($request->get('category_id'));
//
//            $encoder = new JsonEncoder();
//            $normalizer = new ObjectNormalizer();
//
//            $normalizer->setCircularReferenceHandler(function ($object) {
//                    return $object->getId();
//
//                });
//            }



        $serializer = new Serializer(array($normalizer), array($encoder));


        $res = $serializer->serialize($users, 'json');
        return new Response($res);
    }
}