<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SessionService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{

    /**
     * @Route("/login", name="loginpage", methods={"GET"})
     */
    public function getloginpage(LoggerInterface $logger)
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    /**
     * @Route("/login", name="postlogin", methods={"POST"})
     */
    public function postLogin(Request $request, SessionService $sessionService)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["login" => $request->request->get("login")]);

        // SI aucun utilisateur trouvé, redirection
        if (!$user) {
            return $this->redirectToRoute("loginpage");
        }

        // VERIFICATION mot de passe SINON redirection
        if ($user->getPassword() != $request->request->get("password")) {
            return $this->redirectToRoute("loginpage");
        }


        // AFFECTATION ID dans la SESSION
        $sessionService->connectSession($user->getId());

        // REDIRECTION vers l'index ("/")
        $userRank = $user->getRank()->getId();

        dump($userRank);

        die();
        return $this->redirectToRoute("index");
    }

    /**
     * @Route("/deconnexion", name="logout", methods={"GET"})
     */
    public function logout(Request $request, SessionService $sessionService)
    {
        if (!$sessionService->isLogin()) {
            return $this->redirectToRoute("index");
        }

        $sessionService->logoutSession();
        return $this->redirectToRoute("loginpage");
    }
}
