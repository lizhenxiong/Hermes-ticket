<?php

namespace Hermes\WebBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Hermes\WebBundle\Security\CurrentUser;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class BaseController extends Controller
{
    protected $biz;

    public function login($user, $request)
    {
        $currentUser = new CurrentUser($user);

        $token = new UsernamePasswordToken($currentUser, null, 'main', $currentUser->getRoles());
        $this->get('security.token_storage')->setToken($token);

        $event = new InteractiveLoginEvent($request, $token);
        $this->get('event_dispatcher')->dispatch('security.interactive_login', $event);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->biz = $this->container->get('biz');
    }

    protected function getCurrentUser()
    {
        return $this->biz->getUser();
    }
}
