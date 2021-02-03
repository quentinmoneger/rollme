<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Scenario;
use App\Entity\Game;
use App\Entity\User;

class PlayController extends AbstractController
{
    // Home page to select the scenario and parameters
    public function lobby(): Response
    {
        // verify if the user is loged
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } else {
            $gameMaster = $this->getUser();
        }

        $em = $this->getDoctrine()->getManager();
        $scenarios = $em->getRepository(Scenario::class)->findAll();
        $users = $em->getRepository(User::class)->findAll();

        
        if(!empty($_POST)) {

            // Safe scenario entery
            $safe['scenario'] = strip_tags($_POST['scenario']);
            $safe['scenario'] = trim($safe['scenario']);

            $scenario = $em->getRepository(Scenario::class)->findOneBy(['title' => $safe['scenario']]);

            $addUsers = $_POST['user'];
            $users = [];
            foreach ($addUsers as $user) {
                $user = $em->getRepository(User::class)->findOneBy(['username' => $user]);
                $users[] = $user;
            }
            
            
            $game = new Game();
            $game->setScenario($scenario);
            foreach ($users as $user) {
                $game->addUser($user);
            }
            $game->setGameMaster($gameMaster);
            $game->setCurrentFrame('1');

            $em->persist($game);
            $em->flush();
            dd($game);
            return $this->redirectToRoute('play_play', ['id' => $game->getId()]);
        }

        return $this->render('play/lobby.html.twig', [
            'scenarios' => $scenarios,
            'users'     => $users
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
