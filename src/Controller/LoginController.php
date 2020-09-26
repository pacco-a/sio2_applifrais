<?php

namespace App\Controller;

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
        $someStuff = ["app" => "me",
            "you" => "me!?"];
        dump($someStuff);

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    /**
     * @Route("/login", name="postlogin", methods={"POST"})
     */
    public function postLogin(Request $request)
    {
        dump($request->query);
        die();
    }
}
