<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionService $sessionService)
    {
        // ROUTE POUR USERS LOGGES
        if (!$sessionService->isLogin()) {
            return $this->redirectToRoute("loginpage", [
                "err" => "accsden",
            ]);
        }

        // USER RANK POUR LE MENU
        $userRank = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($sessionService->getId())
            ->getRank()
            ->getId();


        return $this->render('index/index.html.twig', [
            "isLogin" => $sessionService->isLogin(),
            "userRank" => $userRank
        ]);
    }
}
