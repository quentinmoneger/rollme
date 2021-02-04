<?php

namespace App\Controller;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Scenario;
use App\Entity\Game;
use App\Entity\User;
use App\Entity\Frame;

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

            // $mailer = new MailerInterface();
            

            // foreach( $users as $user ){
            //     $email = (new Email())
            //     ->from('hello@example.com')
            //     ->to($user->getEmail())
            //     ->subject('Lien partie RollMe')
            //     ->html('<a href="http://localhost:8000/jouer/partie'.$game->getId().'">Voici ton lien !!!</a>');
    
            //     $mailer->send($email);    
            // }


            return $this->redirectToRoute('play_play', ['idGame' => $game->getId()]);
        }

        return $this->render('play/lobby.html.twig', [
            'scenarios' => $scenarios,
            'users'     => $users
        ]);
    }

    public function join(): Response
    {
        if(!empty($_POST)){
            $_POST['joinLink'] = preg_replace(
                '#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i',
                "'<a href=\"$1\" target=\"_blank\">$3</a>$4'",
                $_POST['joinLink']
              ); 
            $url = $_POST['joinLink']; 

            return $this->redirect($url);  
        }

        

        return $this->render('play/join.html.twig');
    }

    public function chargeFrame(int $idGame)
    {
    
        // On vérifie la méthode utilisée
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // On est en GET
            // On vérifie si on a reçu un id    
            if(isset($idGame)){



                // On se connecte
                $em = $this->getDoctrine()->getManager();
                
                //On effectue la requete preparé dans MessagesRepository
                $game = $em->getRepository(Game::class)->find($idGame);
                $currentFrame = $game->getCurrentFrame();

                $scenario = $game->getScenario();
                
                $frame = $em->getRepository(Frame::class)->findImage($currentFrame, $scenario->getId());
         
                $image = $frame->getImage();
                //dd($image);

                // On retourne la requete en Json 
                return  JsonResponse::fromJsonString($image);
            }

        } else {
            // Mauvaise méthode
            http_response_code(405);
            echo json_encode(['message' => 'Mauvaise méthode']);
        }

    }

    public function changeFrame(int $idGame, int $frameId )
    {
    
        $em = $this->getDoctrine()->getManager();

        //dd($nbrFrame);
        //Set the Frame
        $game = $em->getRepository(Game::class)->find($idGame);
        $game->setCurrentFrame($frameId);
        $scenario = $game->getScenario();
        $frames = $em->getRepository(Frame::class)->findByScenarioId($scenario->getId());
        $users = $game->getUsers();
        $gameMaster = $game->getGameMaster();
    
        
        //On exécute en vérifiant si ça fonctionne
        if ($em->flush()) {
            http_response_code(200);
            echo json_encode(['message' => 'Enregistrement effectué']);    
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Une erreur est survenue sur sceneModif']);
        }

        return $this->render('play/play.html.twig', [
            'scenario' => $scenario,
            'frames'=> $frames,
            'users'=> $users,
            'gameMaster'=> $gameMaster,
            'idgame' => $game->getId(), 
        ]);
    }

    public function play(int $idGame): Response
    {

        // verify if the user is loged
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } else {
            $gameMaster = $this->getUser();
        }

        // On se connecte
        $em = $this->getDoctrine()->getManager();

        //On effectue la requete preparé dans MessagesRepository
        $game = $em->getRepository(Game::class)->find($idGame);
        $scenario = $game->getScenario();
        $frames = $em->getRepository(Frame::class)->findByScenarioId($scenario->getId());
        $users = $game->getUsers();
        $gameMaster = $game->getGameMaster();
                

        return $this->render('play/play.html.twig', [
            'scenario' => $scenario,
            'frames'=> $frames,
            'users'=> $users,
            'gameMaster'=> $gameMaster,
            'idgame' => $game->getId(), 
        ]);
    }
}
