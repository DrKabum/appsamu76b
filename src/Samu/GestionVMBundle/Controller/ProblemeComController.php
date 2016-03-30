<?php

namespace Samu\GestionVMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Samu\GestionVMBundle\Entity\ProblemeCom;
use Samu\GestionVMBundle\Form\ProblemeComType;
use Samu\GestionVMBundle\Entity\ProblemeVM;


class ProblemeComController extends Controller
{
	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function addAction(ProblemeVM $probleme, Request $request)
	{
		$commentaire = new ProblemeCom();
		$formulaire = $this->createForm(new ProblemeComType(), $commentaire);

		if($formulaire->handleRequest($request)->isValid())
		{
			$commentaire->setProbleme($probleme);
			$commentaire->setAuthor($this->container->get('security.context')->getToken()->getUser());
			$commentaire->setDate(new \Datetime());
			$em = $this->getDoctrine()->getManager();
			$em->persist($commentaire);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Commentaire ajouté');

			return $this->redirect($this->generateUrl('samu_gestion_vm_problemeView', array('id' => $probleme->getId())));
		}

		return $this->render('SamuGestionVMBundle:ProblemeCom:add.html.twig', array(
			'form' => $formulaire->createView()));
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function ajaxAddAction(ProblemeVM $probleme, Request $request)
	{
		if($request->isXmlHttpRequest()){

			$commentaire = new ProblemeCom();
	
			$commentaire->setContent($_GET['content']);
			$commentaire->setProbleme($probleme);
			$commentaire->setAuthor($this->container->get('security.context')->getToken()->getUser());
			$commentaire->setDate(new \Datetime());
			$em = $this->getDoctrine()->getManager();
			$em->persist($commentaire);
			$em->flush();

			return $this->render('SamuGestionVMBundle:ProblemeCom:commentaireView.html.twig', array(
				'com' => $commentaire
			));
		}
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function ajaxDeleteAction(ProblemeCom $commentaire)
	{
		$em = $this->getDoctrine()->getManager();
		$em->remove($commentaire);
		$em->flush();
	}

	public function ajaxViewAction(ProblemeCom $commentaire)
	{
		return new Response($commentaire->getContent());
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function editAction(ProblemeCom $commentaire, Request $request)
	{
		$formulaire = $this->createForm(new ProblemeComType(), $commentaire);

		if($request->isXmlHttpRequest())
		{
			$commentaire->setContent($request->request->get('modif'));
			$commentaire->setDateModif(new \Datetime());
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			return $this->render('SamuGestionVMBundle:ProblemeCom:commentaireView.html.twig', array(
				'com' => $commentaire
			));
		}
		elseif($formulaire->handleRequest($request)->isValid())
		{
			$commentaire->setDateModif(new \Datetime());
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Modification du commentaire réussie');

			return $this->redirect($this->generateUrl('samu_gestion_vm_problemeView', array('id' => $commentaire->getProblemeId())));
		}

		return $this->render('SamuGestionVMBundle:ProblemeCom:add.html.twig', array(
			'form' => $formulaire->createView()));
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function deleteAction(ProblemeCom $commentaire, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$em->remove($commentaire);
		$em->flush();

		if($request->isXmlHttpRequest())
		{
			return new Response('OK');
		}
		else
		{
			$request->getSession()->getFlashBag()->add('notice', 'Commentaire supprimé');

			return $this->redirect($this->generateUrl('samu_gestion_vm_problemeView', array('id' => $commentaire->getProblemeId())));
		}
	}

	/**
	 * La fonction suivante génère le code HTML du bouton de modification/suppression ($action) en plein ou grisé selon qu'on
	 * est l'auteur du commentaire ou non
	 */
	public function generateButtonAction(ProblemeCom $commentaire, $action)
	{
		$me = $this->getUser();
		$auteur = $commentaire->getAuthor();
		if($action === 'edit' OR $action === 'delete')
		{
			$actionFR   = ($action === 'edit') ? "Modifier" : "Supprimer";
			$imageOK    = $actionFR; #En attendant une image, placer simplement le texte...
			$imageFALSE = $actionFR; #Idem pour l'image grisée}
		} 
			else 
		{
			throw $this->createAccessDeniedException('Impossible de trouver l\'action demandée');
		}

		if($auteur == $me)
		{
			$route = 'samu_gestion_vm_' . $action . 'Com';
			$url = $this->generateUrl($route, array('id' => $commentaire->getId()));

			return $this->render('SamuGestionVMBundle:ProblemeCom:button.html.twig', array(
				'url' => $url,
				'actionFR' => $actionFR));
		}
			else 
		{
			return new Response($actionFR);
		}		
	}
}