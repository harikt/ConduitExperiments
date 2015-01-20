<?php
namespace Controller;

use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService;
use Aura\Auth\Service\LogoutService;
use Psr\Http\Message\ResponseInterface;

class Logout
{
    private $auth;

    private $logout_service;

    public function __construct(Auth $auth, LogoutService $logout_service, ResumeService $resume_service)
    {
        $resume_service->resume($auth);
        $this->auth = $auth;
        $this->logout_service = $logout_service;
    }

    public function __invoke(ResponseInterface $response)
    {
        $this->logout_service->logout($this->auth);
        $response = $response->withHeader('Location', '/login/');
        return $response;
    }
}
