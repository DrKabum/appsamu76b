<?php

namespace Samu\GestionVMBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ProblemeVMRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProblemeVMRepository extends EntityRepository
{
	public function getProblemesVEnCours()
	{
		$qb = $this->createQueryBuilder('p');
		$query = $qb
		  ->select('p')
		  ->where('p.active = 1')
		  ->andWhere('p.staffValidated = 1')
		  ->andWhere($qb->expr()->isNotNull('p.vehicule'))
		  ->leftJoin('p.commentaires', 'c')
		  ->addSelect('c')
		  ->orderBy('p.dateDebut', 'DESC')
		  ->getQuery()
		;

		return $query->getResult();
	}

	public function getProblemesMEnCours()
	{
		$qb = $this->createQueryBuilder('p');
		$query = $qb
		  ->select('p')
		  ->where('p.active = 1')
		  ->andWhere('p.staffValidated = 1')
		  ->andWhere($qb->expr()->isNotNull('p.materiel'))
		  ->leftJoin('p.commentaires', 'c')
		  ->addSelect('c')
		  ->orderBy('p.dateDebut', 'DESC')
		  ->getQuery()
		;

		return $query->getResult();
	}

	public function getProblemesVNonValide()
	{
		$qb = $this->createQueryBuilder('p');
		$query = $qb
			->select('p')
			->where('p.staffValidated = 0')
			->andWhere($qb->expr()->isNotNull('p.vehicule'))
		    ->leftJoin('p.commentaires', 'c')
		    ->addSelect('c')
		    ->orderBy('p.dateDebut', 'DESC')
		    ->getQuery()
		;

		return $query->getResult();
	}

	public function getProblemesMNonValide()
	{
		$qb = $this->createQueryBuilder('p');
		$query = $qb
			->select('p')
			->where('p.staffValidated = 0')
			->andWhere($qb->expr()->isNotNull('p.materiel'))
		    ->leftJoin('p.commentaires', 'c')
		    ->addSelect('c')
		    ->orderBy('p.dateDebut', 'DESC')
		    ->getQuery()
		;

		return $query->getResult();
	}
}
