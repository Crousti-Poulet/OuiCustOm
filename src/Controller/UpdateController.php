<?php

namespace App\Controller;

//use App\Entity\User;
//use App\Form\LoginForm;
use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
//use Symfony\Component\Form\FormBuilderInterface;
//use Webmozart\Assert\Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Validator\Constraints\Email;
//use Symfony\Component\Validator\Constraints\Length;
//use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\Form\Extension\Core\Type\EmailType;
//use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use Symfony\Component\Form\Extension\Core\Type\PasswordType;
//use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
//use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UpdateController extends Controller
{
    /**
     * @Route("/updateProfile", name="updateProfile")
     */

    public function updateProfile(Request $request, ObjectManager $manager)
    {
        //récupère la table User pour y apporter des modifications
        $user = $this->getUser();

        if (!empty($user->getProfilPicture()) ){//permet de vérifier si  le champ image est vide, et ne pas la supprimer lors de l'update de profil
            $oldPicture = $user->getProfilPicture();//on créé une variable oldpicture pour garder l'anciennne image dans le cas où on ne change pas cette dernière
            $picture = new File($this->getParameter('profilPicture_directory') . '/' . $user->getProfilPicture());
            $user->setProfilPicture($picture);
        }

        $form = $this->createFormBuilder($user) // on CREE et CONFIGURE le form grace a createFormBuilder qui sera lié a $user
        ->add('username')
            ->add('email')
            ->add('location')
            ->add('profilPicture', FileType::class)//on précise qu'il va s'agire d'un fichier
                //ainsi l'utilisateur purra choisir une image et la palcer dans ce champs
            ->add('description')
            ->add('category')

            ->getForm() ;       // on le RECUPERE




        $form->handleRequest($request);  // ANALYSE de la requete et du coup symfony lie title content avec $article

        if($form->isSubmitted() && $form->isValid()) {

            if(!$user->getId()){
                $user->setCreationDate(new \Datetime());
            }
                //upload de fichier
            //
            /** @var UploadedFile $file */
            $file = $user->getProfilPicture();

            if( $file instanceof UploadedFile){// si UploadFile est un fichier
                $fileName = $this->generateUniqueFileName().'.'. $file->guessExtension();

                //déplace le fichier image dans le dossier voulu
                $file->move(
                    $this->getParameter('profilPicture_directory'),
                    $fileName
                );
                $user->setProfilPicture($fileName);
            }else{
                $user->setProfilPicture($oldPicture);//dans le cas ou le changement, n'est pas un fichier,(ou vide) on garde l'ancienne image
            }

            $manager->flush();           //on demande au manager de lancer la requete

            return $this->redirectToRoute('artistview');
        }


        return $this->render('security/updateProfile.html.twig',[
            'updateProfile' => $form->createView(), //on envoi a twig le RESULTAT de la fonction createView () == cree un petit objet plutot type affichage.
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

}