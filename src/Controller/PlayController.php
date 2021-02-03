<?php

namespace App\Controller;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $users = $em->getRepository(User::class)->findAllExceptGM($gameMaster->getId());
        
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

            return $this->redirectToRoute('play_play', ['idGame' => $game->getId(), 'nbrFrame' => 0 ]);
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

    public function recover(int $idGame, int $nbrFrame ): Response
    {
    
        // On vérifie la méthode utilisée
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // On est en GET
            // On vérifie si on a reçu un id    
            if(isset($nbrFrame) ){

                // On nettoie l'id 
                $nbrFrame = (int)strip_tags($nbrFrame);

                // Creation d'un methode pour transformer l'objet Php en Objet Json
                $encoder = new JsonEncoder();

                $defaultContext = [ AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                     return $object->getId();
                },];

                $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

                // On se connecte
                $em = $this->getDoctrine()->getManager();

                //On effectue la requete preparé dans MessagesRepository
                $game = $em->getRepository(Game::class)->find($idGame);
    
                // On transforme l'objet Php en objet Json
                $serializer = new Serializer([$normalizer], [$encoder]);
                $messagesJson = $serializer->serialize($game, 'json');

                // On retourne la requete en Json 
                return  JsonResponse::fromJsonString($messagesJson);
            }

        } else {
            // Mauvaise méthode
            http_response_code(405);
            echo json_encode(['message' => 'Mauvaise méthode']);
        }

    }

    public function sceneModif(int $idGame, int $nbrFrame )
    {
        $em = $this->getDoctrine()->getManager();

        //Set the Frame
        $game = $em->getRepository(Game::class)->find($idGame);
        $game->setCurrentFrame($nbrFrame);
          
        // On exécute en vérifiant si ça fonctionne
        if ($em->flush()) {
            http_response_code(200);
            echo json_encode(['message' => 'Enregistrement effectué']);    
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Une erreur est survenue sur sceneModif']);
        }

    }

    public function play(int $idGame, int $nbrFrame = 0): Response
    {
        return $this->render('play/play.html.twig', [
            'idgame' => $idGame,
            'nbrframe'=> $nbrFrame
        ]);
    }
}

