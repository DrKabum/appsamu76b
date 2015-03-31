<?php

namespace Samu\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SamuUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
