<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SessionService;

class VisiteurController extends AbstractController
{
    /**
     * @Route("/visiteur", name="visiteur")
     */
    public function index(SessionService $sessionService)
    {
        //TEST IF RANK (3) "VISITEUR" ELSE redirect "/"
        if(!$sessionService->isRank(3)) {
            return $this->redirectToRoute("index");
        }

        return $this->render('visiteur/index.html.twig');
    }
}
