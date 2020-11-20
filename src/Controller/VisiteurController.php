<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SessionService;

class VisiteurController extends AbstractController
{
    /**
     * @Route("/visiteur", name="visiteur")
     * @IsGranted("ROLE_VIS");
     */
    public function index(SessionService $sessionService, UserRepository $userRepository)
    {

        // USER RANK POUR LE MENU
        $userRank = $userRepository->find($sessionService->getId())
            ->getRank()->getId();

        return $this->render('visiteur/index.html.twig', [
            "isLogin" => $sessionService->isLogin(),
            "userRank" => $userRank
        ]);
    }
}
