<?php

namespace Samu\UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseController;

class ResettingController extends BaseController
{
	public function requestAction()
	{
		return $this->render('SamuUserBundle:Resetting:request.html.twig');
	}
}