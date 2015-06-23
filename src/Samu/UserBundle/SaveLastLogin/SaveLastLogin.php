<?php

namespace Samu\UserBundle\SaveLastLogin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Samu\UserBundle\User;

class SaveLastLogin
{
	public function saveLastLogin(Response $responses, Session $session)
	{
		$lastLogin = $this->container->get('security.context')->getToken()->getUser()->getLastLogin();
		$session->set('last_login', $lastLogin);
	}
}