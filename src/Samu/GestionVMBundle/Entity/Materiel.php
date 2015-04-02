<?php

namespace Samu\GestionVMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Materiel
 *
 * @ORM\Table(name="samu_materiels")
 * @ORM\Entity(repositoryClass="Samu\GestionVMBundle\Entity\MaterielRepository")
 */
class Materiel extends GVMEntities
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
     * @ORM\ManyToMany(targetEntity="Samu\GestionVMBundle\Entity\MaterielCategory", inversedBy="materiels")
     * @ORM\JoinColumn(nullable=true)
     */
    private $categories;

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
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add categories
     *
     * @param \Samu\GestionVMBundle\Entity\MaterielCategory $categories
     * @return Materiel
     */
    public function addCategory(\Samu\GestionVMBundle\Entity\MaterielCategory $categorie)
    {
        $this->categories[] = $categorie;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Samu\GestionVMBundle\Entity\MaterielCategory $categories
     */
    public function removeCategory(\Samu\GestionVMBundle\Entity\MaterielCategory $categorie)
    {
        $this->categories->removeElement($categorie);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
