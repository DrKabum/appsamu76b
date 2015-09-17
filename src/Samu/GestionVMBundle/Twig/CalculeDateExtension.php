<?php

namespace Samu\GestionVMBundle\Twig;

class CalculeDateExtension extends \Twig_Extension
{
	public function getFunctions() 
	{
      return array(
           'getDuree' => new \Twig_Function_Method($this, 'getDureeProbleme'),
      );
	}

	public function getDureeProbleme(\Datetime $dateDebut, $dateFin)
	{
		if(!$dateFin)
		{
			$dateFin = new \Datetime;
		}

		$interval = $dateDebut->diff($dateFin);

		return $interval->format('%a jour(s)');
	}

	public function getName()
	{
		return 'calculateDateExtension';
	}
}