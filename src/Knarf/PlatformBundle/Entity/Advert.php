<?php

namespace Knarf\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="Knarf\PlatformBundle\Repository\AdvertRepository")
 * @Vich\Uploadable
 */
class Advert
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="update_at", type="datetime")
     */
    private $updateAt;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    
    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez remplir le champ")
     * @ORM\Column(name="content", type="text")
     */
    private $content;    
    
    /**
    * 
    * @ORM\Column(name="published", type="boolean")
    */
    private $published = true;
    
    
    /**
     * @var bool
     * @ORM\Column(name="is_admin", type="boolean")
     */
    private $isAdmin = false;
    
    //  === ASSOCIATIONS ===
    
    /**
     * @ORM\ManyToOne(targetEntity="Knarf\UserBundle\Entity\App_User", inversedBy="adverts")
     * @ORM\JoinColumn(name="app_user", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Knarf\PlatformBundle\Entity\Rubrique", inversedBy="adverts")
     * @ORM\JoinColumn(name="rubrique", referencedColumnName="id")
     */
    private $rubrique;

    /**
     * @ORM\ManyToOne(targetEntity="Knarf\PlatformBundle\Entity\Media", cascade={"remove"})
     * @ORM\JoinColumn(name="media", onDelete="SET NULL")
     */
    private $media;
    
    /**
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="advert", cascade={"persist", "remove"})
     */
    private $commentaires;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        $this->setSlug($this->title);
        
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
     * Set content
     *
     * @param string $content
     *
     * @return Advert
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
     * Set published
     * 
     * @param boolean $published
     * 
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }
    
    /**
     * Get published
     * 
     * @return boolean
    */
   public function getPublished()
   {
       return $this->published;
       
   }
   
   /**
    * Set isAdmin
    * 
    * @param boolean $isAdmin Description
    * 
    * @return Advert Description
    */
   public function setIsAdmin($isAdmin)
   {
       $this->isAdmin = $isAdmin;
   }
   
   /**
    * Get isAdmin
    * 
    * @return boolean Description
    */
   public function getIsAdmin() 
   {
       return $this->isAdmin;
   }

    /**
     * Set user
     *
     * @param \Knarf\UserBundle\Entity\App_User $user
     *
     * @return Advert
     */
    public function setUser(\Knarf\UserBundle\Entity\App_User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Knarf\UserBundle\Entity\App_User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->updateAt = new \DateTime();
        $this->commentaires = new ArrayCollection();
    }

    /**
     * Set rubrique
     *
     * @param \Knarf\PlatformBundle\Entity\Rubrique $rubrique
     *
     * @return Rubrique
     */
    public function setRubrique(\Knarf\PlatformBundle\Entity\Rubrique $rubrique = null)
    {
        $this->rubrique = $rubrique;

        return $this;
    }

    /**
     * Get rubrique
     *
     * @return \Knarf\PlatformBundle\Entity\Rubrique
     */
    public function getRubrique()
    {
        return $this->rubrique;
    }

    /**
     * Set media
     * @param \Knarf\PlatformBundle\Entity\Media $media
     * @return Media
     */
    public function setMedia(\Knarf\PlatformBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     * @return \Knarf\PlatformBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Add commentaire
     *
     * @param \Knarf\PlatformBundle\Entity\Commentaire $commentaire
     *
     * @return Advert
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
    
    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return Advert
     */
    public function setUpDateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get upDate
     *
     * @return \DateTime
     */
    public function getUpDateAT()
    {
        return $this->updateAt;
    }


    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Advert
     */
    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function slugify($text)
    {
    // replace non letter or digits by -
    $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

    // trim
    $text = trim($text, '-');

    // transliterate
    if (function_exists('iconv'))
    {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }

    // lowercase
    $text = strtolower($text);

    // remove unwanted characters
    $text = preg_replace('#[^-\w]+#', '', $text);

    if (empty($text))
    {
        return 'n-a';
    }

    return $text;
    }
}
