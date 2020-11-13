<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Security("is_granted('IIS_AUTHENTICATED_FULLY')")
     */
    public function index(SessionService $sessionService)
    {

        // USER RANK POUR LE MENU
        $userRank = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($sessionService->getId())
            ->getRank()
            ->getId();


        return $this->render('index/index.html.twig', [
            "userRank" => $userRank
        ]);
    }
}
