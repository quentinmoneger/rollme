<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PlayController extends AbstractController
{
    // Home page to select the scenario and parameters
    public function lobby(): Response
    {
        return $this->render('play/lobby.html.twig');
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
