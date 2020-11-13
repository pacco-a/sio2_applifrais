<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use App\Form\UserRegisterType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
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
        //build TEMPORARY form for first incription
        //TODO delete when done

        $user = new User();

        $form = $this->createForm(UserRegisterType::class, $user, [
            "action" => $this->generateUrl("register") ]);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render("login/index.html.twig", [
            "registerForm" => $form->createView(),
            "last_username" => $lastUsername,
            "error" => $error,
            "err" => $request->query->get("err")
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        //TODO fin page 2
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {

        return $this->json(["request" => $request->request->all()]);
    }
}
