<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DshMessageController extends AbstractController
{
    public function Index(): Response
    {
        return $this->render('back_end/messages/messages.html.twig');
    }
}