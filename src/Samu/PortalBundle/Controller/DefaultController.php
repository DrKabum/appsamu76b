<?php

namespace Samu\PortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SamuPortalBundle:Default:index.html.twig', array('name' => $name));
    }
}
