<?php
namespace App\Controller\Admin;
use App\Entity\CustomRequest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
class CustomRequestController extends Controller
{
    /**
     * @Route("/admin/requests", name="admin_list_requests")
     */
    public function listRequests(Request $request)
    {
        $custom_requests = $this->getDoctrine()->getManager()->getRepository(CustomRequest::class)->findAll();
        // todo : recherche des requests avec le status "A_VALIDER"
        return $this->render('/admin/custom_request/list.html.twig', [
            'custom_requests' => $custom_requests
        ]);
    }
}