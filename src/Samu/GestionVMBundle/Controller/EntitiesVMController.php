<?php 

namespace Samu\GestionVMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Samu\GestionVMBundle\Entity\Vehicule;
use Samu\GestionVMBundle\Form\VehiculeType;

class EntitiesVMController extends Controller
{
	public function indexAction()
	{
		// Recupère les entités du parc et les envoie à la vue
		$em = $this->getDoctrine()->getManager();

		$vehicules = $em->getRepository('SamuGestionVMBundle:Vehicule')->findAll();
		$materiels = $em->getRepository('SamuGestionVMBundle:Materiel')->findAll();

		return $this->render('SamuGestionVMBundle:EntitiesVM:index.html.twig', array(
			'vehicules' => $vehicules,
			'materiels' => $materiels
		));
	}

	public function addAction($type, Request $request)
	{
		$entity = new Vehicule();
		$formulaire = $this->createForm(new VehiculeType(), $entity);

		if($formulaire->handleRequest($request)->isValid())
		{
			$em = $this->getDoctrine($request)->getManager();
			$em->persist($entity);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Nouveau véhicule créé.');

			return $this->redirect($this->generateUrl('samu_gestion_vm_entitiesAdd', array('type' => 'vehicule')));
		}

		return $this->render('SamuGestionVMBundle:EntitiesVM:add.html.twig', array(
			'form' => $formulaire->createView()
		));
	}

	public function deleteAction()
	{

	}

	public function editAction()
	{

	}

	public function viewAction()
	{

	}
}