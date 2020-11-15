<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use App\Form\UserRegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    /**
     * @Route("/login", name="security_login")
     */
    public function getloginpage( AuthenticationUtils $authenticationUtils, Request $request, UserRepository $userRepository)
    {
        //build TEMPORARY form for first incription
        //TODO delete when done

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render("login/login.html.twig", [
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
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function register( UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {

        $user = new User();

        $form = $this->createForm(UserRegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setUsername($form->getData()->getUsername());
            $user->setFirstname($form->getData()->getFirstname());
            $user->setPassword($passwordEncoder->encodePassword($user, $form->getData()->getPassword()));
            $user->setEmail($form->getData()->getEmail());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->redirectToRoute("admin");
            //TODO changer la redirection vers la page d'ajout de comptes

        } else {
            $this->redirectToRoute("loginpage");
        }

    }
}
