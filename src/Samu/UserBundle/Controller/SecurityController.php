<?php

namespace Samu\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
	public function renderLogin(array $data)
	{
		return $this->render('SamuUserBundle:Security:login.html.twig', $data);
	}
}