<?php

namespace Samu\GestionVMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProblemeVM
 *
 * @ORM\Table(name="samu_problemes")
 * @ORM\Entity(repositoryClass="Samu\GestionVMBundle\Entity\ProblemeVMRepository")
 */
class ProblemeVM
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Samu\UserBundle\Entity\User")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime")
     */
    private $dateFin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModif", type="datetime", nullable=true)
     */
    private $dateModif;

    /**
     * @var boolean
     *
     * @ORM\Column(name="staffValidated", type="boolean")
     */
    private $staffValidated = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     * 
     * @ORM\ManyToOne(targetEntity="Samu\GestionVMBundle\Entity\Vehicule", inversedBy="problemes")
     */
    private $vehicule;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Samu\GestionVMBundle\Entity\Materiel")
     */
    private $materiel;

    /**
     * @ORM\OneToMany(targetEntity="Samu\GestionVMBundle\Entity\ProblemeCom", mappedBy="probleme")
     */
    private $commentaires;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {

    }

    /**
     * Set title
     *
     * @param string $title
     * @return ProblemeVM
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return ProblemeVM
     */
    public function setAuthor(\Samu\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return ProblemeVM
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return ProblemeVM
     */
    public function setDateDebut(\Datetime $dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return ProblemeVM
     */
    public function setDateFin(\Datetime $dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set staffValidated
     *
     * @param boolean $staffValidated
     * @return ProblemeVM
     */
    public function setStaffValidated($staffValidated)
    {
        $this->staffValidated = $staffValidated;

        return $this;
    }

    /**
     * Get staffValidated
     *
     * @return boolean 
     */
    public function getStaffValidated()
    {
        return $this->staffValidated;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return ProblemeVM
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set vehicule
     *
     * @param \Samu\GestionVM\Entity\Vehicule $vehicule
     * @return ProblemeVM
     */
    public function setVehicule(\Samu\GestionVMBundle\Entity\Vehicule $vehicule = null)
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    /**
     * Get vehicule
     *
     * @return \Samu\GestionVM\Entity\Vehicule 
     */
    public function getVehicule()
    {
        return $this->vehicule;
    }

    public function getCible()
    {
        return ($vehicule) ? $this->cible = $vehicule : $this->cible = $materiel;
    }

    /**
     * Set materiel
     *
     * @param \Samu\GestionVMBundle\Entity\Materiel $materiel
     * @return ProblemeVM
     */
    public function setMateriel(\Samu\GestionVMBundle\Entity\Materiel $materiel = null)
    {
        $this->materiel = $materiel;

        return $this;
    }

    /**
     * Get materiel
     *
     * @return \Samu\GestionVMBundle\Entity\Materiel 
     */
    public function getMateriel()
    {
        return $this->materiel;
    }

    /**
     * Set dateModif
     *
     * @param \DateTime $dateModif
     * @return ProblemeVM
     */
    public function setDateModif(\Datetime $dateModif)
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    /**
     * Get dateModif
     *
     * @return \DateTime 
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }

    /**
     * Add commentaires
     *
     * @param \Samu\GestionVMBundle\Entity\ProblemeCom $commentaires
     * @return ProblemeVM
     */
    public function addCommentaire(\Samu\GestionVMBundle\Entity\ProblemeCom $commentaires)
    {
        $this->commentaires[] = $commentaires;

        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param \Samu\GestionVMBundle\Entity\ProblemeCom $commentaires
     */
    public function removeCommentaire(\Samu\GestionVMBundle\Entity\ProblemeCom $commentaires)
    {
        $this->commentaires->removeElement($commentaires);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }
}
