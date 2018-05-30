<?php

namespace App\Controller\Admin;

use App\Entity\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RequestController extends Controller
{
    /**
     * @Route("/admin/requests", name="admin_list_requests")
     */
    public function listRequests(Request $request)
    {
        $requests = $this->getDoctrine()->getManager()->getRepository(Request::class)->findAll();
        // todo : recherche des requests avec le status "A_VALIDER"

        return $this->render('/admin/requests', [
            'requests' => $requests
        ]);
    }
}
