<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Scenario;

class HomeController extends AbstractController
{

    public function index(): Response
    {

        $em = $this->getDoctrine()->getManager();
        $scenarios = $em->getRepository(Scenario::class)->findAll();

        return $this->render('home/index.html.twig', [
            'scenarios' => $scenarios
        ]);
    }
}
