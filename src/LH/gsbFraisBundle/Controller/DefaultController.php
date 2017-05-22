<?php

// src/LH/gsbFraisBundle/Controller/IndexController.php

namespace LH\gsbFraisBundle\Controller;

// N'oubliez pas ce use :
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
  public function indexAction()
  {
    return $this->render('LHgsbFraisBundle:Default:index.html.twig');
  }




}
