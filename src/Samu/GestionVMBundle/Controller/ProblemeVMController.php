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

	public function viewAction()
	{

	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function addAction($typePb, Request $request)
	{
		
		if($typePb === "pbvehicule") 
		{
			$probleme = new ProblemeVM();
			$formulaire = $this->createForm(new ProblemeVType(), $probleme);

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

	public function editAction()
	{

	}

	public function deleteAction()
	{

	}

	public function reportAction()
	{

	}

	public function validateAction()
	{

	}
}