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
		
		if($typePb === "pbvehicule") 
		{
			$probleme = new ProblemeVM();
			$formulaire = $this->createForm(new ProblemeVMType(), $probleme);

			if($formulaire->handleRequest($request)->isValid())
			{
				$em = $this->getDoctrine($request)->getManager();
				$probleme->setActive(1);
				$probleme->setAuthor($this->container->get('security.context')->getToken()->getUser());
				$em->persist($probleme);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Problème signalé, le staff Matériel le traitera dès que possible');

				return $this->redirect($this->generateUrl('samu_gestion_vm_index', array('page' => 1)));
			}

			return $this->render('SamuGestionVMBundle:ProblemeVM:add.html.twig', array(
				'form' => $formulaire->createView()
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
	public function reportAction()
	{

	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function validateAction()
	{

	}
}