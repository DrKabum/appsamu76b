<?php

namespace Samu\GestionVMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vehicule
 *
 * @ORM\Table(name="samu_vehicules")
 * @ORM\Entity(repositoryClass="Samu\GestionVMBundle\Entity\VehiculeRepository")
 */
class Vehicule extends GVMEntities
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="immatriculation", type="string", length=255)
     */
    private $immatriculation;

    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="string", length=255)
     */
    private $modele;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordreDepart", type="smallint")
     */
    private $ordreDepart;

    /**
     * @var string
     *
     * @ORM\Column(name="typeVehicule", type="string", length=255)
     */
    private $typeVehicule;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set immatriculation
     *
     * @param string $immatriculation
     * @return Vehicule
     */
    public function setImmatriculation($immatriculation)
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    /**
     * Get immatriculation
     *
     * @return string 
     */
    public function getImmatriculation()
    {
        return $this->immatriculation;
    }

    /**
     * Set modele
     *
     * @param string $modele
     * @return Vehicule
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return string 
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set ordreDepart
     *
     * @param integer $ordreDepart
     * @return Vehicule
     */
    public function setOrdreDepart($ordreDepart)
    {
        $this->ordreDepart = $ordreDepart;

        return $this;
    }

    /**
     * Get ordreDepart
     *
     * @return integer 
     */
    public function getOrdreDepart()
    {
        return $this->ordreDepart;
    }

    /**
     * Set typeVehicule
     *
     * @param string $typeVehicule
     * @return Vehicule
     */
    public function setTypeVehicule($typeVehicule)
    {
        $this->typeVehicule = $typeVehicule;

        return $this;
    }

    /**
     * Get typeVehicule
     *
     * @return string 
     */
    public function getTypeVehicule()
    {
        return $this->typeVehicule;
    }
}
