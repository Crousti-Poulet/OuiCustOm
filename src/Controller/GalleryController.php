<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\FileValidator;

class GalleryController extends Controller
{

    // Affichage de galerie d'artiste
    /**
     * @Route("/gallery/{id}", name="gallery")
     */
    public function showGallery(User $artist)
    {

        $images = $artist->getImages();

        // $userName = $gallery->getUser()->getUsername();
        if (!$images) {
            throw $this->createNotFoundException(
                'Ce customiseur n\a pas encore ajouté de photos'
            );
        }

        return $this->render('gallery/gallery.html.twig', [
            'images' => $images,
            'artist' => $artist
        ]);
    }

    // Affichage de galerie d'artiste
    /**
     * @Route("/gallery/remove/{id}", name="remove_image_gallery")
     */
    public function removeImageGallery(Image $image)
    {
        // vérifications pour que n'importe qui ne puisse supprimer une image
        // - si l'image appartient bien à l'utilisateur connecté
        if($this->getUser() == $image->getUser()) {
            // supprimer l'image de la liste des images de l'utilisateur
            $this->getUser()->removeImage($image);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

        }
        // TODO else afficher un message d'erreur ou redirect sur une page d'erreur
        return $this->redirectToRoute('gallery', ['id' => $this->getUser()->getId()]);
    }

    /**
     * @Route("/addPicture", name="addPicture")
     * 
     */
    public function addPictureArtiste(Request $request, ObjectManager $manager)
    {
        //on recupere l'user par son id de la table User            
        $user = $this->getUser();    

        // on cree un objet image
        $image = new Image();
        
        // on lui ajoute l'id de l'utilisateur recuperer
        $image->setUser($user);

        
        //création et configuration du form
        //on donne les conditions de validation de ces champs
        $form = $this->createFormBuilder($image)
                    ->add('name', TextType::class,[
                        'required' => true,
                        'constraints' => [ 
                            new Length([
                                'min' => 3,
                                'max' => 15,
                                'minMessage' => "Minimum 3 caractères",
                                'maxMessage' => "Maximum 15 caractères"
                            ]),
                            new NotBlank( [
                                'message' => "Champs non-rempli"
                            ])

                        ],
                    ])
                    //on précise qu'il va s'agire d'un fichier
                     //ainsi l'utilisateur purra choisir une image et la palcer dans ce champs
                     ->add('link', FileType::class,[
                         'required' => true,
                         'constraints' => [
                            new NotBlank( [
                                'message' => "Choisir une image"
                            ])
                        ],
                     ])
                    ->getForm(); //on récupère le formulaire

                        
        $form->handleRequest($request);  // ANALYSE de la requete et ducou symfony lié title content avec $user

        //Verification de la soumission du formulaire
        if($form->isSubmitted() && $form->isValid()) {

            if(!$image->getId()){
                $image->setDate(new \Datetime());
            }
            
            // //upload de fichier
           
            /** @var UploadedFile $file */
            $file = $image->getLink();

            if( $file instanceof UploadedFile){// si UploadFile est un fichier
                $fileName = $this->generateUniqueFileName().'.'. $file->guessExtension();

                //déplace le fichier image dans le dossier voulu
                $file->move(
                    $this->getParameter('galleryPicture_directory'),
                    $fileName
                );
                $image->setLink($fileName);

            }

            // $user->addImage($image);
            $image = $form->getData();    // on garde les donnée sounmi au formulaire
            $user->addImage($image);
            
            $manager->persist($image);  // on demande au manager de se preparer à persister l'image


            $manager->flush();           //on demande au manager de lancer la requete
           // $user_id = $user->getId();
           return $this->redirectToRoute('gallery', ['id' => $this->getUser()->getId()]);
        }


        return $this->render('gallery/galleryAdd.html.twig',[
            'addPicForm' => $form->createView(),
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
