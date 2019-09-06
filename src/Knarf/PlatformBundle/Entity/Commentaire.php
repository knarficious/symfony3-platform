<?php

namespace Knarf\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Comment as BaseComment;
use FOS\CommentBundle\Model\SignedCommentInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Knarf\UserBundle\Entity\App_User;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="Knarf\PlatformBundle\Repository\CommentaireRepository")
 */
class Commentaire extends BaseComment implements SignedCommentInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    protected $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_publication", type="datetime")
     */
    protected $datePublication;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_mise_ajour", type="datetime")
     */
    protected $dateMiseAJour;
    
    //  === ASSOCIATIONS ===
    /**
     * @ORM\ManyToOne(targetEntity="Advert", inversedBy="commentaires")
     * @ORM\JoinColumn(name="advert_id", referencedColumnName="id")
     */
    protected $advert;
    
    /**
     * @ORM\ManyToOne(targetEntity="Knarf\UserBundle\Entity\App_User", inversedBy="commentaires")
     * @ORM\JoinColumn(name="app_user_id", referencedColumnName="id")
     * @var App_User
     */
    protected $user;
    
    /**
     * Thread of this comment
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="Knarf\PlatformBundle\Entity\Thread")     * 
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id")
     */
    protected $thread;
    
//    public function __construct() {
//        $this->datePublication = new \DateTime();
//        $this->dateMiseAJour = new \DateTime();
//       // $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
//    }


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
     * @param \Knarf\UserBundle\Entity\App_User $user
     *
     * @return Commentaire
     */
    public function setUser(\Knarf\UserBundle\Entity\App_User $user = null)
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

    /**
     * Set commentaire
     *
     * @param \Knarf\PlatformBundle\Entity\Commentaire $commentaire
     *
     * @return Commentaire
     */
    public function setCommentaire(\Knarf\PlatformBundle\Entity\Commentaire $commentaire = null)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return \Knarf\PlatformBundle\Entity\Commentaire
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
    
    /**
     * Add commentaire
     *
     * @param \Knarf\PlatformBundle\Entity\Commentaire $commentaire
     *
     * @return Commentaire
     */
    public function addCommentaire(\Knarf\PlatformBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires[] = $commentaire;

        return $this;
    }

    /**
     * Remove commentaire
     *
     * @param \Knarf\PlatformBundle\Entity\Commentaire $commentaire
     */
    public function removeCommentaire(\Knarf\PlatformBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires->removeElement($commentaire);
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



    public function getAuthor() 
    {
        return $this->user;
    }

    public function setAuthor(UserInterface $author) 
    {
        $this->user = $author;
    }
    

    public function getAuthorName()
    {
        if (null === $this->getAuthor()) {
            return 'Anonymous';
        }

        return $this->getAuthor()->getUsername();
    }
    
    public function getThread() 
    {
        return $this->thread;
    }
    

}
