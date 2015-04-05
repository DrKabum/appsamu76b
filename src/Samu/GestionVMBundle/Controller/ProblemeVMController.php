<?php

namespace Samu\GestionVMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Samu\GestionVMBundle\Entity\ProblemeVM;
use Samu\GestionVMBundle\Form\ProblemeVMType;
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
		  ->getProblemes($page, $nbPerPage)
		;		

		$nbPages = ceil(count($listPb)/$nbPerPage);

		if($page>$nbPages) {
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
	public function viewAction($id)
	{
		$probleme = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('SamuGestionVMBundle:ProblemeVM')
						 ->findOneById($id);

		if(!$probleme){
			throw $this->createNotFoundException("La page demandée n'existe pas.");
		}

		return $this->render('SamuGestionVMBundle:ProblemeVM:view.html.twig', array(
			'probleme' => $probleme));
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function addAction($typePb, Request $request)
	{					
		$formulaire = createFormWithType($typePb);

		if($formulaire['form']->handleRequest($request)->isValid())
		{
			submitProblem($request, $formulaire['entity'], true);

			$request->getSession()->getFlashBag()->add('notice', 'Problème ajouté à la liste des problèmes en cours.');

			return $this->redirect($this->generateUrl('samu_gestion_vm_index', array('page' => 1)));
		}

		return $this->render('SamuGestionVMBundle:ProblemeVM:add.html.twig', array(
			'form' => $formulaire['form']->createView()
			));
		}
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function editAction(ProblemeVM $probleme, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$formulaire = $this->createForm(new ProblemeVMType(), $probleme);

		if($formulaire->handleRequest($request)->isValid())
		{
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Modifications réussie.');

			return $this->redirect($this->generateUrl('samu_gestion_vm_problemeView', array('id' => $probleme->getId())));
		}

		return $this->render('SamuGestionVMBundle:ProblemeVM:edit.html.twig', array(
			'form'     => $formulaire->createView(),
			'probleme' => $probleme));		
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function deleteAction(ProblemeVM $probleme)
	{
		$em = $this->getDoctrine()->getManager()
		$em->remove($probleme);
		$em->flush();

		$this->get('session')->getFlashBag()->add('notice', 'Le probleme a été définitivement supprimé.');

		return $this->render(generateUrl('samu_gestion_vm_index'));
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function reportAction($typePb, Request $request)
	{
		$formulaire = createFormWithType($typePb);

		if($formulaire['form']->handleRequest($request)->isValid())
		{
			submitProblem($request, $formulaire['entity']);
			$request->getSession()->getFlashBag()->add('notice', 'Le problème a été soumis au staff. Il est d\'ores et déjà visible dans la section des problèmes non validés.');

			return $this->render(generateUrl('samu_gestion_vm_index'));
		}

		return $this->render('SamuGestionVMBundle:ProblemeVM:add.html.twig', array(
			'form' => $formulaire['form']->createView()
			));
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function validateAction($typePb, Request $request)
	{

	}

	public function submitProblem(Request $request, ProblemeVM $probleme, $staffmode = false)
	{
		$em = $this->getDoctrine($request)->getManager();
		($staffmode) ? $probleme->setActive(1) : $probleme->setActive(0);
		$probleme->setAuthor($this->container->get('security.context')->getToken()->getUser());
		$em->persist($probleme);
		$em->flush();
	}

	public function createFormWithType($type, $entity = null)
	{
		if($type ==='vehicule') {
			if(null === $entity) { $entity = new ProblemeVM();}
			$formulaire = $this->createForm(new ProblemeVType(), $entity);
		} elseif($type ==='materiel') {
			if(null === $entity) { $entity = new ProblemeVM();}
			$formulaire = $this->createForm(new ProblemeMType(), $entity);
		} else {
			throw $this->createNotFoundException('Demande d\'ajout incorrecte');
		}

		return array(
			'entity' => $entity,
			'form'   => $formulaire);
	}
}