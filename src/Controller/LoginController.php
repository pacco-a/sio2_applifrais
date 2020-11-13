<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserLoginType;
use App\Service\SessionService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    /**
     * @Route("/login", name="security_login")
     */
    public function getloginpage(AuthenticationUtils $authenticationUtils, Request $request, UserRepository $userRepository)
    {
        dump($userRepository->find(8)->getRoles());
        die();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("login/index.html.twig", [
            "last_username" => $lastUsername,
            "error" => $error,
            "err" => $request->query->get("err"),
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {
        //TODO fin page 2
    }
}
