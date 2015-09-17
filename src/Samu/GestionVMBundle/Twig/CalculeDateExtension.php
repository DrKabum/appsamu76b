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

	public function getDureeProbleme(\Datetime $dateDebut, \Datetime $dateFin)
	{
		$interval = $dateDebut->diff($dateFin);
		$interval->format('%a jours');

		return $interval;
	}

	public function getName()
	{
		return 'calculateDateExtension';
	}
}