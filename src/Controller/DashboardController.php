<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DashboardController extends AbstractController
{
    /**
     * @Route("/account/a/dashboard", name="admin.dashboard")
     * @return Response
     */
    public function Index(): Response
    {
        return $this->render('back_end/admin.html.twig');
    }

    /**
     * @Route("/account/p/dashboard", name="pro.dashboard")
     * @return Response
     */
    public function ProDashboard() : Response
    {
        return $this->render('back_end/pro.html.twig');
    }
}