<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\UserRepository;

class SessionService
{
    private $session;
    private $userRepository;

    public function __construct(SessionInterface $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function connectSession($id)
    {
        $this->session->set("loginId", $id);
    }

    public function logoutSession() {
        $this->session->remove("loginId");
    }

    public function getId() {
        return $this->session->get("loginId");
    }

    public function isLogin(){
        if($this->session->get("loginId")) {
            return true;
        } else {
            return false;
        }
    }

    public function isRank($rank) {

        $userId = $this->session->get("loginId");

        // SI PAS D'USER : FALSE
        if(!$userId) {
            return false;
        }

        // SI YA USER : le fetcher
        $userRankId = $this->userRepository->find($userId)->getRank()->getId();

        return $userRankId == $rank;

    }
}