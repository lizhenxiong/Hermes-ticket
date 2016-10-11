<?php

namespace Hermes\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Hermes\WebBundle\Controller\BaseController;

class DefaultController extends BaseController
{
    public function indexAction(Request $request)
    {
        $user = $this->getUserService()->getUser(1);
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    public function getUserService()
    {
        return $this->biz['user_service'];
    }
}
