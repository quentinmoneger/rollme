<?php

namespace App\Controller;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Messages;


class MessagesController extends AbstractController
{

    public function add(): Response
    {
        // On vérifie la méthode
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // On vérifie si l'utilisateur est connecté  
            if ($this->getUser()) {
                // L'utilisateur est connecté
                // On récupère le message
                $donneesJson = file_get_contents('php://input');

                // On convertit les données en objet PHP
                $donnees = json_decode($donneesJson);

                // On vérifie si on a un message
                if (isset($donnees->message) && !empty($donnees->message)) {
                    // On a un message

                    // On se connecte
                    $em = $this->getDoctrine()->getManager();
                    
                    //On effectue la requete
                    $message = new Messages();
                    $message->setMessage($donnees->message);
                    $message->setCreatedAt(new \DateTime('now'));
                    $message->setUserId($this->getUser());
                    
                    // On le stocke
                    $em->persist($message);

                    // On exécute en vérifiant si ça fonctionne
                    if ($em->flush()) {
                        http_response_code(200);
                        echo json_encode(['message' => 'Enregistrement effectué']);    
                    } else {
                        http_response_code(400);
                        echo json_encode(['message' => 'Une erreur est survenue']);
                    }
                } else {
                    // Pas de message
                    http_response_code(400);
                    echo json_encode(['message' => 'Le message est vide']);
                }
            } else {
                // Non connecté
                http_response_code(400);
                echo json_encode(['message' => 'Vous devez vous connecter']);
            }
        } else {
            // Mauvaise méthode
            http_response_code(405);
            echo json_encode(['message' => 'Mauvaise méthode']);
        }

        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }

    public function recover(int $lastid): Response
    {
        // On vérifie la méthode utilisée
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // On est en GET
            // On vérifie si on a reçu un id    
            if(isset($lastid) ){

                // On nettoie l'id 
                $lastid = (int)strip_tags($lastid);

                // Creation d'un methode pour transformer l'objet Php en Objet Json
                $encoder = new JsonEncoder();

                $defaultContext = [ AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                     return $object->getId();
                },];

                $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

                // On se connecte
                $em = $this->getDoctrine()->getManager();

                //On effectue la requete preparé dans MessagesRepository
                $messages = $em->getRepository(Messages::class)->findByIdSup($lastid);
    
                // On transforme l'objet Php en objet Json
                $serializer = new Serializer([$normalizer], [$encoder]);
                $messagesJson = $serializer->serialize($messages, 'json');

                // On retourne la requete en Json 
                return  JsonResponse::fromJsonString($messagesJson);
            }

        } else {
            // Mauvaise méthode
            http_response_code(405);
            echo json_encode(['message' => 'Mauvaise méthode']);
        }
    }

    public function index(): Response
    {

        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }
}

