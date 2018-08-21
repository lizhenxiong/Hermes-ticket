<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController  extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('home.html.twig');
    }
}