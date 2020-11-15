<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\UserRegisterType;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/gestmember", name="gest_member")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function gestmember(AuthenticationUtils $authenticationUtils)
    {
        $user = new User();

        $form = $this->createForm(UserRegisterType::class, $user, [
            "action" => $this->generateUrl("register") ]);

        return $this->render('admin/gestmember.html.twig', [
            "registerForm" => $form->createView(),
        ]);
    }
}
