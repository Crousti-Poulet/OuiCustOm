<?php
namespace App\Controller\Admin;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
class UserListController extends Controller
{
    /**
     * Liste de toutes les demandes de customization
     * @Route("/admin/userlist", name="admin_list_users")
     */
    public function listUsers(Request $request)
    {
        $user_list = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        return $this->render('/admin/userlist/userList.html.twig', [
            'user_list' => $user_list
        ]);
    }

}