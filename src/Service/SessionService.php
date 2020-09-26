<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function loginSession($id)
    {
        $this->session->set("loginId", $id);
    }

    public function isLogin(){
        if($this->session->get("loginId")) {
            return true;
        } else {
            return false;
        }
    }
}