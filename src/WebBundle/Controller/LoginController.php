<?php 
namespace Hermes\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Hermes\WebBundle\Controller\BaseController;

class LoginController extends BaseController
{
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('WebBundle:Login:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));
    }

    public function logoutAction(Request $request)
    {
    }

    public function loginCheckAction(Request $request)
    {
    }

    public function registerAction(Request $request)
    {
        if ('POST' == $request->getMethod()) {
            $user = $request->request->all();
            $user = $this->getUserService()->register($user);
            $this->login($user, $request);
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('WebBundle:Register:register.html.twig');
    }

    protected function getUserService()
    {
        return $this->biz['user_service'];
    }
}