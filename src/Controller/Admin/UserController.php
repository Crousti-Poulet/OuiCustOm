<?php
namespace App\Controller\Admin;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
class UserController extends Controller
{
    /**
     * Liste de tous les utilisateurs
     * @Route("/admin/user/list", name="admin_list_users")
     */
    public function listUsers(Request $request)
    {
        $user_list = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        return $this->render('/admin/user/list.html.twig', [
            'user_list' => $user_list
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     */
    public function deleteUser(User $user)
    {
        //TODO message de confirmation

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_list_users');
    }


}