<?php
namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/", name="default_admin")
     *
     */
    public function defaultAdmin(Request $request)
    {
        return $this->render('admin/default/index.html.twig',[
        ]);
    }
}