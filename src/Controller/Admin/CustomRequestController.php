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
    public function listCustomRequests(Request $request)
    {
        $custom_requests = $this->getDoctrine()->getManager()->getRepository(CustomRequest::class)->findAllByStatus(CustomRequest::STATUS_A_VALIDER);
        return $this->render('/admin/custom_request/list.html.twig', [
            'custom_requests' => $custom_requests
        ]);
    }

    public function validateCustomRequest(CustomRequest $customRequest)
    {
        $customRequest->setStatus(CustomRequest::STATUS_EN_ATTENTE);

        // TODO : bouton qui appelle cette fonction
        $em = $this->getDoctrine()->getManager();
        $em->persist($customRequest);
        $em->flush();
    }
}