<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FicheController extends AbstractController
{
    /**
     * @Route("/fiche", name="fiche")
     */
    public function index(SessionService $sessionService, UserRepository $userRepository)
    {
        // TODO faire fiche

        //ROUTE UNIQUEMENT POUR VISITEURS
        if(!$sessionService->isRank(3)) {
            return $this->redirectToRoute("index");
        }

        // USER RANK POUR LE MENU **SI** L'USER EST CONNECTE
        if ($sessionService->isLogin()) {
            $userRank = $userRepository->find($sessionService->getId())
                ->getRank()->getId();
        } else {
            $userRank = 0;
        }

        return $this->render('fiche/index.html.twig', [
            "isLogin" => $sessionService->isLogin(),
            "userRank" => $userRank
        ]);
    }
}
