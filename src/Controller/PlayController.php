<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Scenario;

class PlayController extends AbstractController
{
    // Home page to select the scenario and parameters
    public function lobby(): Response
    {
        $gameMaster = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $scenarios = $em->getRepository(Scenario::class)->findAll();

        
        if(!empty($_POST)) {
            

            
            // $game = new Game();
            // $game->setScenario($_POST['scenario']);
            // $game->addUser($_POST['user']);
            // $game->setGameMaster($gameMaster);
            // $game->setCurrentFrame('1');

            // $em->persist($game);
            // $em->flush();
        }

        return $this->render('play/lobby.html.twig', [
            'scenarios' => $scenarios
        ]);
    }

    public function join(): Response
    {
        return $this->render('play/join.html.twig');
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
