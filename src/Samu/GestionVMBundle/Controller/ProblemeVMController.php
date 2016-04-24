<?php

namespace Samu\GestionVMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Samu\GestionVMBundle\Entity\ProblemeVM;
use Samu\GestionVMBundle\Form\ProblemeVType;
use Samu\GestionVMBundle\Form\ProblemeMType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProblemeVMController extends Controller
{
	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function indexAction($action)
	{
		($action == 'index') ? $validation = false : $validation = true;

		$vehiculesAvecProblemesEnCours;

			if ($validation) 
		{
			$vehiculesAvecProblemesEnCours = $this
				->getDoctrine()
				->getManager()
				->getRepository('SamuGestionVMBundle:Vehicule')
				->findVehiculesWithProblemsNonValides()
			;
		}
			else
		{
			$vehiculesAvecProblemesEnCours = $this
				->getDoctrine()
				->getManager()
				->getRepository('SamuGestionVMBundle:Vehicule')
				->findVehiculesWithProblems()
			;
		}

		return $this->render('SamuGestionVMBundle:ProblemeVM:index.html.twig', array(

			'vehiculesAvecProblemesEnCours'  => $vehiculesAvecProblemesEnCours,
			'validation'       => $validation//nous ne sommes pas entrain de valider des problèmes (info nécessaire au template)
		));
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function viewAction(ProblemeVM $probleme)
	{
		$this->get('session')->set($probleme->getId(), 1);
		return $this->render('SamuGestionVMBundle:ProblemeVM:view.html.twig', array(
			'probleme'   => $probleme,
			'validation' => 1
		));
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function addAction($typePb, Request $request)
	{					
		if($request->isXmlHttpRequest())
		{
			$formulaire = $this->createFormWithType(null, $typePb);
		
			if($formulaire['form']->handleRequest($request)->isValid())
			{
				$probleme = $this->submitProblem($request, $formulaire['probleme'], true);
	
				$request->getSession()->getFlashBag()->add('notice', 'Problème ajouté à la liste des problèmes en cours.');
	
				return $this->render('SamuGestionVMBundle:ProblemeVM:problemeView.html.twig', array(
					'probleme' => $probleme,
					'validation' => 0
				));
			}
	
			return $this->render('SamuGestionVMBundle:ProblemeVM:add.html.twig', array(
				'form'    => $formulaire['form']->createView(),
				'add' => true
				));
		}
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function editAction(ProblemeVM $probleme, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$formulaire = $this->createFormWithType($probleme);

		if($formulaire['form']->handleRequest($request)->isValid())
		{
			$probleme->setDateModif(new \Datetime());
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Modifications réussie.');

			return $this->redirect($this->generateUrl('samu_gestion_vm_index'));
		}

		return $this->render('SamuGestionVMBundle:ProblemeVM:edit.html.twig', array(
			'form'     => $formulaire['form']->createView(),
			'probleme' => $probleme));		
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function deleteAction(ProblemeVM $probleme)
	{
		$em = $this->getDoctrine()->getManager();
		$em->remove($probleme);
		$em->flush();

		$this->get('session')->getFlashBag()->add('notice', 'Le probleme a été définitivement supprimé.');

		return $this->redirect($this->generateUrl('samu_gestion_vm_index'));
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function reportAction($typePb, Request $request)
	{
		if($request->isXmlHttpRequest()) 
		{
			$formulaire = $this->createFormWithType(null, $typePb);

			if($formulaire['form']->handleRequest($request)->isValid())
			{
				$this->submitProblem($request, $formulaire['probleme']);
				$request->getSession()->getFlashBag()->add('notice', 'Le problème a été soumis au staff.');

				return $this->redirect($this->generateUrl('samu_gestion_vm_index', array('action' => 'valider')));
			}

			return $this->render('SamuGestionVMBundle:ProblemeVM:add.html.twig', array(
				'form'    => $formulaire['form']->createView(),
				'add' => false
				));
		}
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function validateAction(ProblemeVM $probleme)
	{
		$em = $this->getDoctrine()->getManager();
		$probleme->setStaffValidated(1);

		$em->flush();

		$this->get('session')->getFlashBag()->add('notice', 'Ce problème est maintenant pris en compte par le staff');

		return $this->redirect($this->generateUrl('samu_gestion_vm_index', array('action' => 'valider')));

	}

	/**
	 *@Security("has_role('ROLE_STAFF')")
	 */
	public function classerAction(ProblemeVM $probleme)
	{
		$em = $this->getDoctrine()->getManager();

		$probleme->setActive(0);
		$em->flush();

		$this->get('session')->getFlashBag()->add("notice", "Le problème n°" . $probleme->getId() . " a été validé.");

		return $this->redirect($this->generateUrl('samu_gestion_vm_index'));
	}

	/**
	 *@Security("has_role('ROLE_USER')")
	 */
	public function submitProblem(Request $request, ProblemeVM $probleme, $staffmode = false)
	{
		$em = $this->getDoctrine($request)->getManager();
		$probleme->setActive(1);
		($staffmode) ? $probleme->setStaffValidated(1) : $probleme->setStaffValidated(0);
		$probleme->setAuthor($this->container->get('security.context')->getToken()->getUser());
		$em->persist($probleme);
		$em->flush();

		return $probleme;
	}

	public function createFormWithType(ProblemeVM $probleme = null, $typePb = null)
	{
		if($typePb ==='pbvehicule' OR !is_null($probleme) AND $probleme->isPbVehicule()) {
			if(!$probleme) { $probleme = new ProblemeVM();}
			$formulaire = $this->createForm(new ProblemeVType(), $probleme);
		} elseif($typePb ==='pbmateriel' OR !is_null($probleme) AND $probleme->isPbMateriel()) {
			if(!$probleme) { $probleme = new ProblemeVM();}
			$formulaire = $this->createForm(new ProblemeMType(), $probleme);
		} else {
			throw $this->createNotFoundException('Demande d\'ajout incorrecte');
		}				

		return array(
			'probleme' => $probleme,
			'form'   => $formulaire);
	}

	public function isProblemNewAction(ProblemeVM $probleme, Request $request)
	{
		$session    = $this->get('session');
		$lastLogin  = $this->getUser()->getLastLoginSaved();
		$sessionLog = $session->has($probleme->getId());
		
		if($lastLogin > $probleme->getDateDebut() /*OR $lastLogin > $probleme->getDateModif() VERIFIER QUE LA DATE DE MODIF N'EST PAS NULLE AVANT*/)
		{
			$isNew = false;
		} 
			else if($sessionLog) 
		{
			$isNew = false;
		}
			else if($request->isXmlHttpRequest())
		{
			$this->get('session')->set($probleme->getId(), 1);
			$isNew = false;
		}
			else
		{
			$isNew = true;
		}

		return $this->render('SamuGestionVMBundle:ProblemeVM:news-indicator.html.twig', array(
			'isNew' 	=> $isNew));
	}

	public function countNonValideAction()
	{
		$count = $this->getDoctrine()->getManager()->getRepository('SamuGestionVMBundle:ProblemeVM')->countAllProblemeNonValide();

		return $this->render('SamuGestionVMBundle:ProblemeVM:new-validation.html.twig', array(
			'decompte'      => $count
		));
	}
}