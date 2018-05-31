<?php
namespace App\Controller\Admin;
use App\Entity\CustomRequest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
class CustomRequestController extends Controller
{
    /**
     * Liste de toutes les demandes de customization
     * @Route("/admin/customrequests", name="admin_list_custom_requests")
     */
    public function listCustomRequests(Request $request)
    {
        $custom_requests = $this->getDoctrine()->getManager()->getRepository(CustomRequest::class)->findAll();
        return $this->render('/admin/custom_request/list.html.twig', [
            'custom_requests' => $custom_requests
        ]);
    }

    /**
     * @Route("/admin/customrequests/{status}", name="admin_list_custom_requests_status")
     */
    public function listCustomRequestsByStatus(Request $request, string $status)
    {
        $custom_requests = $this->getDoctrine()->getManager()->getRepository(CustomRequest::class)->findAllByStatus($status);
        return $this->render('/admin/custom_request/list.html.twig', [
            'custom_requests' => $custom_requests
        ]);
    }

    /**
     * @Route("/admin/customrequests/validate/{id}", name="admin_custom_request_validate")
     */
    public function validateCustomRequest(CustomRequest $customRequest)
    {
        $customRequest->setStatus(CustomRequest::STATUS_EN_ATTENTE);

        // TODO : bouton qui appelle cette fonction
        $em = $this->getDoctrine()->getManager();
        //$em->persist($customRequest);
        $em->flush();
        return $this->redirectToRoute('admin_list_custom_requests');
    }
}