<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayController extends AbstractController
{
    // Home page to select the scenario and parameters
    public function index(): Response
    {
        return $this->render('play/index.html.twig');
    }


    // In game
    public function play(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        // Get the scenario
        $scenario = $em->getRepository(Scenario::class)->find($id);
        
        return $this->render('play/play.html.twig', [
            'scenario' => $scenario,
        ]);
    }
}
