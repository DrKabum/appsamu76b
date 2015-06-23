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
	public function indexAction($page)
	{
		if($page < 1) {
			throw $this->createNotFoundException("La page demandée n'existe pas.");
		}

		$nbPerPage = 5; // (A mettre dans un fichier config plutôt)

		$listPb = $this->getDoctrine()
		  ->getManager()
		  ->getRepository('SamuGestionVMBundle:ProblemeVM')
		  ->getProblemesEnCours($page, $nbPerPage)
		;		

		$nbPages = ceil(count($listPb)/$nbPerPage);

		if($nbPages === 0) {
			$this->get('session')->getFlashBag()->add('notice', 'Aucun problème en cours.');
		}
		elseif($page>$nbPages) {
			throw $this->createNotFoundException("La page demandée n'existe pas.");
		}

		return $this->render('SamuGestionVMBundle:ProblemeVM:index.html.twig', array(
			'listProblemes' => $listPb,
			'nbPages'       => $nbPages,
			'page'          => $page
		));
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function viewAction(ProblemeVM $probleme)
	{
		$this->get('session')->set($probleme->getId(), 1);
		return $this->render('SamuGestionVMBundle:ProblemeVM:view.html.twig', array(
			'probleme' => $probleme));
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function addAction($typePb, Request $request)
	{					
		$formulaire = $this->createFormWithType($typePb);

		if($formulaire['form']->handleRequest($request)->isValid())
		{
			$this->submitProblem($request, $formulaire['entity'], true);

			$request->getSession()->getFlashBag()->add('notice', 'Problème ajouté à la liste des problèmes en cours.');

			return $this->redirect($this->generateUrl('samu_gestion_vm_index', array('page' => 1)));
		}

		return $this->render('SamuGestionVMBundle:ProblemeVM:add.html.twig', array(
			'form' => $formulaire['form']->createView()
			));
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function editAction($typePb, ProblemeVM $probleme, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$formulaire = $this->createFormWithType($typePb, $probleme);

		if($formulaire['form']->handleRequest($request)->isValid())
		{
			$probleme->setDateModif(new \Datetime());
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Modifications réussie.');

			return $this->redirect($this->generateUrl('samu_gestion_vm_problemeView', array('id' => $probleme->getId())));
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
		$formulaire = $this->createFormWithType($typePb);

		if($formulaire['form']->handleRequest($request)->isValid())
		{
			$this->submitProblem($request, $formulaire['entity']);
			$request->getSession()->getFlashBag()->add('notice', 'Le problème a été soumis au staff. Il est d\'ores et déjà visible dans la section des problèmes non validés.');

			return $this->redirect($this->generateUrl('samu_gestion_vm_index'));
		}

		return $this->render('SamuGestionVMBundle:ProblemeVM:add.html.twig', array(
			'form' => $formulaire['form']->createView()
			));
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

		return $this->redirect($this->generateUrl('samu_gestion_vm_problemeView', array('id' => $probleme->getId())));

	}

	public function classerAction(ProblemeVM $probleme)
	{
		$em = $this->getDoctrine()->getManager();

		$probleme->setActive(0);
		$em->flush();

		return $this->redirect($this->generateUrl('samu_gestion_vm_problemeView', array('id' => $probleme->getId())));
	}

	public function submitProblem(Request $request, ProblemeVM $probleme, $staffmode = false)
	{
		$em = $this->getDoctrine($request)->getManager();
		$probleme->setActive(1);
		($staffmode) ? $probleme->setStaffValidated(1) : $probleme->setStaffValidated(0);
		$probleme->setAuthor($this->container->get('security.context')->getToken()->getUser());
		$em->persist($probleme);
		$em->flush();
	}

	public function createFormWithType($type, $entity = null)
	{
		if($type ==='pbvehicule') {
			if(null === $entity) { $entity = new ProblemeVM();}
			$formulaire = $this->createForm(new ProblemeVType(), $entity);
		} elseif($type ==='pbmateriel') {
			if(null === $entity) { $entity = new ProblemeVM();}
			$formulaire = $this->createForm(new ProblemeMType(), $entity);
		} else {
			throw $this->createNotFoundException('Demande d\'ajout incorrecte');
		}

		return array(
			'entity' => $entity,
			'form'   => $formulaire);
	}

	public function isProblemNewAction(ProblemeVM $probleme)
	{
		$session = $this->get('session');
		$lastLogin = $this->getUser()->getLastLogin();
		$sessionLog = $session->get($probleme->getId());

		($lastLogin < $probleme->getDateDebut() OR $lastLogin < $probleme->getDateModif() OR $sessionLog) ? $isNew = true : $isNew = false;


		return $this->render('SamuGestionVMBundle:ProblemeVM:news-indicator.html.twig', array(
			'isNew' 	=> $isNew,
			'probleme' 	=> $probleme->getId()));
	}
}