<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
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
    public function getloginpage(LoggerInterface $logger, Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserLoginType::class, $user, [
            "action" => $this->generateUrl("postlogin")
        ]);

        return $this->render('login/index.html.twig', [
            'userLoginForm' => $form->createView(),
            "err" => $request->query->get("err")
        ]);
    }

    /**
     * @Route("/login", name="postlogin", methods={"POST"})
     */
    public function postLogin(Request $request, SessionService $sessionService)
    {
        $user = new User();

        $form = $this->createForm(UserLoginType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
        } else {
            $this->redirectToRoute("loginpage");
        }

        $userObject = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["login" => $user->getLogin()]);

        // SI aucun utilisateur trouvé, redirection
        if (!$userObject) {
            return $this->redirectToRoute("loginpage", ["err" => "nouser"]);
        }

        // VERIFICATION mot de passe SINON redirection
        if ($userObject->getPassword() != $user->getPassword()) {
            return $this->redirectToRoute("loginpage", ["err" => "wrgpass"]);
        }


        // AFFECTATION ID dans la SESSION
        $sessionService->connectSession($userObject->getId());

        // REDIRECTION vers l'index ("/")

        $userRank = $userObject->getRank()->getId();

        switch ($userRank) {
            //ADMIN
            case 1:
                dump("TODO: admin route");
                die();
                break;
            // COMPTABLE
            case 2:
                dump("TODO: comptable route");
                die();
                break;
            // VISITEUR
            case 3:
                return $this->redirectToRoute("visiteur");
        }

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
