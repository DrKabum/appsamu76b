<?php 

namespace Samu\GestionVMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Samu\GestionVMBundle\Entity\Vehicule;
use Samu\GestionVMBundle\Entity\Materiel;
use Samu\GestionVMBundle\Form\VehiculeType;
use Samu\GestionVMBundle\Form\MaterielType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class EntitiesVMController extends Controller
{
	/**
	 * @Security("has_role('ROLE_USER')")
	 */
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

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function addAction($type, Request $request)
	{
		if($type ==='vehicule') {
			$entity = new Vehicule();
			$formulaire = $this->createForm(new VehiculeType(), $entity);
		} elseif($type ==='materiel') {
			$entity = new Materiel();
			$formulaire = $this->createForm(new MaterielType(), $entity);
		} else {
			throw $this->createNotFoundException('Demande d\'ajout incorrecte');
		}

		if($formulaire->handleRequest($request)->isValid())
		{
			$em = $this->getDoctrine($request)->getManager();
			$em->persist($entity);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Nouveau véhicule créé.');

			return $this->redirect($this->generateUrl('samu_gestion_vm_entitiesAdd', array('type' => $type)));
		}

		return $this->render('SamuGestionVMBundle:EntitiesVM:add.html.twig', array(
			'form' => $formulaire->createView()
		));
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function deleteAction($type, $id, Request $request)
	{
		$typePath = 'SamuGestionVMBundle:' . ucfirst($type);
		$em = $this->getDoctrine()->getManager();
		$cible = $em->getRepository($typePath)->findOneById($id);

		if(!$cible)
		{
			throw $this->createNotFoundException("L'entité demandée n'existe pas.");
		}

		$em->remove($cible);
		$em->flush();

		$request->getSession()->getFlashBag()->add('notice', 'L\élément sélectionné a correctement été supprimé.');

		return $this->redirect($this->generateUrl('samu_gestion_vm_entitiesIndex'));
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function editAction()
	{

	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function viewAction()
	{

	}
}