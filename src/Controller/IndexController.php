<?php

namespace App\Controller;

use App\Service\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Security("is_granted('ROLE_VIS') or is_granted('ROLE_COMP')")
     */
    public function index(SessionService $sessionService)
    {


        return $this->render('index/index.html.twig', [
        ]);
    }
}
