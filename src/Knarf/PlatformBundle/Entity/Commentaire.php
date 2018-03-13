<?php

namespace Knarf\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="Knarf\PlatformBundle\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublication", type="datetime")
     */
    private $datePublication;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMiseAJour", type="datetime")
     */
    private $dateMiseAJour;
    
    //  === ASSOCIATIONS ===
    /**
     * @ORM\ManyToOne(targetEntity="Advert", inversedBy="commentaires")
     * @ORM\JoinColumn(name="advert_id", referencedColumnName="id")
     */
    private $advert;
    
    /**
     * @ORM\ManyToOne(targetEntity="Knarf\UserBundle\Entity\User", inversedBy="commentaires")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    public function __construct() {
        $this->datePublication = new \DateTime();
        $this->dateMiseAJour = new \DateTime();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Commentaire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Commentaire
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set dateMiseAJour
     *
     * @param \DateTime $dateMiseAJour
     *
     * @return Commentaire
     */
    public function setDateMiseAJour($dateMiseAJour)
    {
        $this->dateMiseAJour = $dateMiseAJour;

        return $this;
    }

    /**
     * Get dateMiseAJour
     *
     * @return \DateTime
     */
    public function getDateMiseAJour()
    {
        return $this->dateMiseAJour;
    }

    /**
     * Set advert
     *
     * @param \Knarf\PlatformBundle\Entity\Advert $advert
     *
     * @return Commentaire
     */
    public function setAdvert(\Knarf\PlatformBundle\Entity\Advert $advert = null)
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get advert
     *
     * @return \Knarf\PlatformBundle\Entity\Advert
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * Set user
     *
     * @param \Knarf\UserBundle\Entity\User $user
     *
     * @return Commentaire
     */
    public function setUser(\Knarf\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \KnarfUserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
