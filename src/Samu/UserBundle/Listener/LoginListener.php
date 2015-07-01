<?php

/**
 * Ce listener enregistre la dernière date de connexion avant que FOSUserBundle ne la change
 */

namespace Samu\UserBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

class LoginListener
{
	private $securityContext;

	private $em;

	public function __construct(SecurityContext $securityContext, Doctrine $doctrine)
	{
		$this->securityContext = $securityContext;
		$this->em		   	   = $doctrine->getEntityManager();
	}

	public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
	{
		if($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY') OR $this->securityContext->isGranted('IS_AUTHENTCATED_REMEMBERED'))
		{
			$user = $event->getAuthenticationToken()->getUser();
			$lastLogin = $user->getLastLogin();
			$user->setLastLoginSaved($lastLogin);
		}
	}
}