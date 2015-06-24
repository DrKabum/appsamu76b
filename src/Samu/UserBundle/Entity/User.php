<?php

namespace Samu\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 */
class User extends BaseUser
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(name="lastloginsaved", type="datetime")
   */
  protected $lastLoginSaved;

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
     * Set lastLoginSaved
     *
     * @param \DateTime $lastLoginSaved
     * @return User
     */
    public function setLastLoginSaved($lastLoginSaved)
    {
        $this->lastLoginSaved = $lastLoginSaved;

        return $this;
    }

    /**
     * Get lastLoginSaved
     *
     * @return \DateTime 
     */
    public function getLastLoginSaved()
    {
        return $this->lastLoginSaved;
    }
}
