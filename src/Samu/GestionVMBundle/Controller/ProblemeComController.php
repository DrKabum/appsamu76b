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
}