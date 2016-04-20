<?php

namespace Samu\GestionVMBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * VehiculeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VehiculeRepository extends EntityRepository
{
	public function findAllByDepart()
	{
		$qb = $this->createQueryBuilder('v');

		$query = $qb
			->select('v')
			->orderBy('v.ordreDepart', 'ASC')
			->getQuery()
		;

		return $query->getResult();
	}
}
