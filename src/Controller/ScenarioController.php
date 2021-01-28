<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Respect\Validation\Validator as v;
use App\Entity\Scenario;
use App\Entity\Frame;

class ScenarioController extends AbstractController
{

    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $scenarios = $em->getRepository(Scenario::class)->findAll();


        return $this->render('scenario/scenario.html.twig', [
            'scenarios' => $scenarios
        ]);
    }

    public function create(): Response
    {

        $em = $this->getDoctrine()->getManager();

        $errors = [];

        if (!empty($_POST)) {

            $safe = array_map('trim', array_map('strip_tags', $_POST));

            // Validations
            if (!v::length(5, 100)->validate($safe['title'])) {
                $errors[] = 'Le titre doit comporter entre 5 et 100 caractères maximum.';
            }

            $scenario = $em->getRepository(Scenario::class)->findOneBy(['title' => $safe['title']]);
            if ($scenario !== null) {
                $errors[] = 'Ce nom est déjà utilisé.';
            }

            if (!v::length(30, 1000)->validate($safe['resume'])) {
                $errors[] = 'Le résumé doit comporter entre 30 et 1000 caractères maximum.';
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {

                // Server issue
                if ($_FILES['image']['error'] != UPLOAD_ERR_OK) {
                    $errors[] = 'Une erreur est survenue lors du transfert de l\'image';
                } else {

                    // Size-Type issue
                    $maxSize = 6 * 1000 * 1000;
                    if ($_FILES['image']['size'] > $maxSize) {
                        $errors[] = 'L\'image est trop volumineuse...';
                    } else {
                        $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/webp'];
                        if (!in_array($_FILES['image']['type'], $allowMimesTypes)) {
                            $errors[] = 'Le type de fichier est invalide';
                        }
                    }
                }
            }


            // Upload on server
            if (count($errors) === 0) {

                // Building the image dir
                $rootPublic = $_SERVER['DOCUMENT_ROOT'];
                $dirTarget = 'assets/scenario/' . $safe['title'] . '/';
                $dirOutput = $rootPublic . $dirTarget;

                // Creat the folder
                if (!is_dir($dirOutput)) {
                    mkdir($dirOutput, 0777);
                }

                // If there is an image
                if (!empty($_FILES['image']['tmp_name'])) {

                    // Standardisation of the extension
                    switch ($_FILES['image']['type']) {
                        case 'image/jpg':
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            $extension = 'jpg';
                            break;

                        case 'image/png':
                            $extension = 'png';
                            break;

                        case 'image/webp':
                            $extension = 'webp';
                            break;
                    }

                    $filename = 'illusration.' . $extension;

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $dirTarget . $filename)) {
                        die('Erreur d\'upload fichier images');
                    }

                    $linkImage = $dirTarget . $filename;
                } else {
                    $linkImage = '/assets/default/illustration.jpg';
                }

                $scenario = new Scenario();
                $scenario->setTitle($safe['title']);
                $scenario->setResume($safe['resume']);
                $scenario->setImage($linkImage);

                $em->persist($scenario);
                $em->flush();

                $this->addFlash('success', 'Votre scénario a été créé avec succès');

                return $this->redirectToRoute('scenario_index');
            } else {

                $this->addFlash('danger', implode('<br>', $errors));
            }
        } // endif !empty($_POST


        return $this->render('scenario/create.html.twig');
    }

    public function update(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $scenario = $em->getRepository(Scenario::class)->find($id);

        // Get all frames from this scenario
        $frames = $scenario->getFrames();

        // If id doesn't exist, retrun scenarios page
        if (!$scenario) {
            return $this->redirectToRoute('scenario_index');
        }


        if (!empty($_POST)) {

            $errors = [];

            $safe = array_map('trim', array_map('strip_tags', $_POST));

            // Validations
            if (!v::length(5, 100)->validate($safe['title'])) {
                $errors[] = 'Le titre doit comporter entre 5 et 100 caractères maximum.';
            }

            if (!v::length(30, 1000)->validate($safe['resume'])) {
                $errors[] = 'Le résumé doit comporter entre 30 et 1000 caractères maximum.';
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {

                // Server issue
                if ($_FILES['image']['error'] != UPLOAD_ERR_OK) {
                    $errors[] = 'Une erreur est survenue lors du transfert de l\'image';
                } else {

                    // Size-Type issue
                    $maxSize = 6 * 1000 * 1000;
                    if ($_FILES['image']['size'] > $maxSize) {
                        $errors[] = 'L\'image est trop volumineuse...';
                    } else {
                        $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/webp'];
                        if (!in_array($_FILES['image']['type'], $allowMimesTypes)) {
                            $errors[] = 'Le type de fichier est invalide';
                        }
                    }
                }
            }


            // Upload on server
            if (count($errors) === 0) {

                $scenario->setTitle($safe['title']);
                $scenario->setResume($safe['resume']);


                // If there is an image upload and there is no image already (shouldn't)
                if (!empty($_FILES['image']['tmp_name'])) {

                    // Building the image dir
                    $rootPublic = $_SERVER['DOCUMENT_ROOT'];
                    $publicOutput = 'assets/scenario/' . $safe['title'] . '/';
                    $dirOutput = $rootPublic . $publicOutput;

                    // Creat the folder if doesn't exist (shouldn't)
                    if (!is_dir($dirOutput)) {
                        mkdir($dirOutput, 0777);
                    }

                    // Standardisation of the extension
                    switch ($_FILES['image']['type']) {
                        case 'image/jpg':
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            $extension = 'jpg';
                            break;

                        case 'image/png':
                            $extension = 'png';
                            break;

                        case 'image/webp':
                            $extension = 'webp';
                            break;
                    }

                    $filename = 'illusration.' . $extension;

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $dirOutput . $filename)) {
                        die('Erreur d\'upload fichier images');
                    }
                    $linkImage = $dirOutput . $filename;

                    $scenario->setImage($linkImage);
                }
                
                // Execute
                $em->flush();

                // $this->addFlash('success', 'Votre scénario a été modifié avec succès');

                return $this->redirectToRoute('scenario_index');
            } else {

                $this->addFlash('danger', implode('<br>', $errors));
            }
        } // endif !empty($_POST

        return $this->render('scenario/update.html.twig', [
            'scenario' => $scenario,
            'frames'   => $frames
        ]);
    }

    public function frame(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $scenario = $em->getRepository(Scenario::class)->find($id);
        
        // Get all frames from the scenario
        $frames = $scenario->getFrames();

        return $this->render('scenario/frame.html.twig', [
            'scenario' => $scenario,
            'frames'   => $frames
        ]);
    }


    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $scenario = $entityManager->getRepository(Scenario::class)->find($id);

        function rrmdir($dir)
        {
            if (is_dir($dir)) {
                $files = scandir($dir);
                foreach ($files as $file) {
                    if ($file != "." && $file != "..") {
                        if (filetype($dir . "/" . $file) == "dir") {
                            rrmdir($dir . "/" . $file);
                        } else {
                            unlink($dir . "/" . $file);
                        }
                    }
                }
                rmdir($dir);
            }
        }
        $dir = 'assets/scenario/' . $scenario->getTitle();
        rrmdir($dir);

        // Delete the scenario's folder

        // Delete the scenario
        $entityManager->remove($scenario);
        $entityManager->flush();


        return $this->redirectToRoute('scenario_index');
    }
}
