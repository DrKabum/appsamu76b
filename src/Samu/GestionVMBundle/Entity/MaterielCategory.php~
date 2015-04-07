<?php

namespace Samu\GestionVMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MaterielCategory
 *
 * @ORM\Table(name="samu_materielcategories")
 * @ORM\Entity(repositoryClass="Samu\GestionVMBundle\Entity\MaterielCategoryRepository")
 */
class MaterielCategory
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Samu\GestionVMBundle\Entity\Materiel", mappedBy="categories")
     * @ORM\JoinColumn(nullable=true)
     */
    private $materiels;


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
     * Set name
     *
     * @param string $name
     * @return MaterielCategory
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
     * Set materiels
     *
     * @param array $materiels
     * @return MaterielCategory
     */
    public function setMateriels($materiels)
    {
        $this->materiels = $materiels;

        return $this;
    }

    /**
     * Get materiels
     *
     * @return array 
     */
    public function getMateriels()
    {
        return $this->materiels;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->materiels = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add materiels
     *
     * @param \Samu\GestionVMBundle\Entity\Materiel $materiels
     * @return MaterielCategory
     */
    public function addMateriel(\Samu\GestionVMBundle\Entity\Materiel $materiel)
    {
        $this->materiels[] = $materiel;

        return $this;
    }

    /**
     * Remove materiels
     *
     * @param \Samu\GestionVMBundle\Entity\Materiel $materiels
     */
    public function removeMateriel(\Samu\GestionVMBundle\Entity\Materiel $materiel)
    {
        $this->materiels->removeElement($materiel);
    }
}
