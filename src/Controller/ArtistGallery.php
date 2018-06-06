<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Image;
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

class ArtistGallery extends Controller
{
    /**
     * @Route("/ArtistGallery", name="ArtistGallery")
     */

    public function galleryAction(Request $request)
    {
        return $this->render('gallery/gallerie.html.twig');
    }
}