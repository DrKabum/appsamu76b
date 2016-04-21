<?php 

namespace Samu\GestionVMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Samu\GestionVMBundle\Entity\Vehicule;
use Samu\GestionVMBundle\Entity\Materiel;
use Samu\GestionVMBundle\Entity\MaterielCategory;
use Samu\GestionVMBundle\Form\VehiculeType;
use Samu\GestionVMBundle\Form\MaterielType;
use Samu\GestionVMBundle\Form\MaterielCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class EntitiesVMController extends Controller
{
	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();

		$vehicules = $em->getRepository('SamuGestionVMBundle:Vehicule')->findAllByDepart();

		return $this->render('SamuGestionVMBundle:EntitiesVM:index.html.twig', array(
			'vehicules' => $vehicules
		));
	}
	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function indexParTypeAction($type)
	{
		// Recupère les entités du parc et les envoie à la vue
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository($this->findTypeRepositoryPath($type))->findAll();
		$template = 'SamuGestionVMBundle:EntitiesVM:' . $type . 's_index.html.twig';

		return $this->render($template, array(
			'entities' => $entities,
			'type'   => $type
		));
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function addAction($type, Request $request)
	{
		//if($request->isXmlHttpRequest())
		//{
			$f = $this->createFormWithType($type);

			if($f['form']->handleRequest($request)->isValid())
			{
				$em = $this->getDoctrine($request)->getManager();
				$em->persist($f['entity']);
				$em->flush();

				//$request->getSession()->getFlashBag()->add('notice', 'Nouveau véhicule/matériel créé.');

				return $this->render('SamuGestionVMBundle:EntitiesVM:cartouche_vehicule.html.twig', array(
					'vehicule' => $f['entity']
				));
			}

			return $this->render('SamuGestionVMBundle:EntitiesVM:add.html.twig', array(
				'form' => $f['form']->createView()
			));
		//}
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function deleteAction($type, $id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$cible = $em->getRepository($this->findTypeRepositoryPath($type))->findOneById($id);

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
	public function editAction($type, $id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository($this->findTypeRepositoryPath($type))->findOneById($id);

		if(!$entity)
		{
			throw $this->createNotFoundException("L'entité demandée n'existe pas.");
		}

		$f = $this->createFormWithType($type, $entity);

		if($f['form']->handleRequest($request)->isValid())
		{
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Entité modifiée');

			return $this->redirect($this->generateUrl('samu_gestion_vm_entitiesView', array('type' => $type, 'id' => $id)));
		}

		return $this->render('SamuGestionVMBundle:EntitiesVM:edit.html.twig', array(
			'form'   => $f['form']->createView(),
			'entity' => $entity));
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function viewAction($type, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository($this->findTypeRepositoryPath($type))->findOneById($id);

		if(null === 'entity') {
			return $this->createNotFoundException('L\'entitée demandée n\'existe pas');
		}

		return $this->render('SamuGestionVMBundle:EntitiesVM:view.html.twig', array(
			'entity' => $entity,
			'type'   => $type));
	}

	/**
	 *@Security("has_role('ROLE_STAFF')")
	 */

	public function addMatCatAction(Request $request)
	{
		$matcat = new MaterielCategory();
		$formulaire = $this->createForm(new MaterielCategoryType(), $matcat);

		if($formulaire->handleRequest($request)->isValid())
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($matcat);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'Catégorie ajoutée');

			return $this->redirect($this->generateUrl('samu_gestion_vm_entitiesMatCatViewIndex'));
		}

		return $this->render('SamuGestionVMBundle:EntitiesVM:addMatCat.html.twig', array(
			'form' => $formulaire->createView()));
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function deleteMatCatAction(MaterielCategory $matcat)
	{
		$em = $this->getDoctrine()->getManager();
		$em->remove($matcat);
		$em->flush();
		$request->getSession()->getFlashBag()->add('notice', 'Catégorie supprimée');

		return $this->redirect($this->generateUrl('samu_gestion_vm_entitiesMatCatViewIndex'));
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function editMatCatAction(MaterielCategory $matcat, Request $request)
	{
		$formulaire = $this->createForm(new MaterielCategoryType(), $matcat);

		if($formulaire->handleRequest($request)->isValid())
		{
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Catégorie modifiée');

			return $this->redirect($this->generateUrl('samu_gestion_vm_entitiesIndex', array(
				'type' => 'materiel'
			)));
		}

		return $this->render('SamuGestionVMBundle:EntitiesVM:addMatCat.html.twig', array(
			'form' => $formulaire->createView()));
	}

	/**
	 * @Security("has_role('ROLE_STAFF')")
	 */
	public function indexMatCatAction()
	{
		$em = $this->getDoctrine()->getManager();
		$categories = $em->getRepository('SamuGestionVMBundle:MaterielCategory')->findAll();

		return $this->render('SamuGestionVMBundle:EntitiesVM:indexMatCat.html.twig', array(
			'categories' => $categories));
	}

	public function findTypeRepositoryPath($type) 
	{
		return 'SamuGestionVMBundle:' . ucfirst($type);
	}

	public function createFormWithType($type, $entity = null)
	{
		if($type ==='vehicule') {
			if(null === $entity) { $entity = new Vehicule();}
			$formulaire = $this->createForm(new VehiculeType(), $entity);
		} elseif($type ==='materiel') {
			if(null === $entity) { $entity = new Materiel();}
			$formulaire = $this->createForm(new MaterielType(), $entity);
		} else {
			throw $this->createNotFoundException('Demande d\'ajout incorrecte');
		}

		return array(
			'entity' => $entity,
			'form'   => $formulaire);
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function historiqueViewAction($id, $type)
	{
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository($this->findTypeRepositoryPath($type))->findOneById($id);
		$template = 'SamuGestionVMBundle:EntitiesVM:historique-' . $type . '.html.twig';

		return $this->render($template, array(
			'entity' => $entity
		));
	}

	public function sortOrdreDepartAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$i = 1;

		if (isset($_GET['vehiculeid'])) {

			foreach ($_GET['vehiculeid'] as $id) {
				$vehicule = $em->getRepository('SamuGestionVMBundle:Vehicule')->findOneById($id);
				$vehicule->setOrdreDepart($i);
				$vehicule->setOperationnel(true);
				$em->flush();
				$i++;
			}
		}

		if(isset($_GET['vehiculedownid']))
		{
			foreach ($_GET['vehiculedownid'] as $id) {
					$vehicule = $em->getRepository('SamuGestionVMBundle:Vehicule')->findOneById($id);
					$vehicule->setOperationnel(false);
					$vehicule->setOrdreDepart(0);
					$em->flush();
			}
		}

		return new Response;
	}

	public function getOrdreDepartAction(Vehicule $vehicule)
	{
		$em = $this->getDoctrine()->getManager();

		return new Response($vehicule->getOrdreDepart());
	}
}