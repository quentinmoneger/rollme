<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScenarioController extends AbstractController
{

    public function index(): Response
    {

        return $this->render('scenario/scenario.html.twig');
    }

    public function create(): Response
    {

        return $this->render('scenario/create.html.twig');
    }
}
