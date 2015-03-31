<?php

namespace Samu\GestionVMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classe abstraite décrivant les entités traitées par GVM
 *
 * @ORM\MappedSuperclass()
 */

abstract class GVMEntities 
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=255, unique=true)
	 */
	private $name;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="anne", type="datetime")
	 */
	private $annee;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="operationnel", type="boolean")
	 */
	private $operationnel;

    /**
     * Set name
     *
     * @param string $name
     * @return GVMEntities
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set annee
     *
     * @param \DateTime $annee
     * @return GVMEntities
     */
    public function setAnnee(\Datetime $annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return \DateTime 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set operationnel
     *
     * @param boolean $operationnel
     * @return GVMEntities
     */
    public function setOperationnel($operationnel)
    {
        $this->operationnel = $operationnel;

        return $this;
    }

    /**
     * Get operationnel
     *
     * @return boolean 
     */
    public function getOperationnel()
    {
        return $this->operationnel;
    }
}
