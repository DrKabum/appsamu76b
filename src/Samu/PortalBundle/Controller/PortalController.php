<?php

namespace Samu\PortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PortalController extends controller
{
	public function indexAction()
	{
		//TODO : Trouver un moyen de générer les applis automatiquement...

		return $this->render('SamuPortalBundle:Portal:index.html.twig');
	}
}