<?php

namespace Knarf\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Knarf\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * 
 * @Vich\Uploadable
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;
    
    /**
     * 
     * @ORM\Column(name="adresseIp", type="string")
     */
    private $adresseIp;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles = array();
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="isActive", type="integer")
     */
    private $isActive;
    
    /**
     * 
     * @Assert\Length(max=4096)
     */
    private $plainPassword;
    
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Assert\File(
     * 		maxSize="10M",
     * 		mimeTypes={"image/png", "image/jpeg", "image/gif"})
     * @Vich\UploadableField(mapping="upload_media", fileNameProperty="nomMedia", nullable=true)
     * 
     * @var File
     */
    private $mediaFile;

     /**
     * @var string
     *
     * @ORM\Column(name="nom_media", type="string", length=255, nullable=true)
     */
    private $nomMedia;
    
    // === ASSOCIATIONS ===
    
    /**
     * @ORM\OneToMany(targetEntity="Knarf\PlatformBundle\Entity\Advert", mappedBy="user", cascade={"persist", "remove"})
     */
    private $adverts;
    
    /**
     * @ORM\OneToMany(targetEntity="Knarf\PlatformBundle\Entity\Commentaire", mappedBy="user", cascade={"persist", "remove"})
     */
    private $commentaires;
    
    public function __construct()
    {
        //parent::__construct();
        
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->roles = array('ROLE_USER');
        $this->createdAt = new \DateTime;
        $this->updatedAt= new \DateTime;
        $this->adverts = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
    
    public function eraseCredentials()
    {
		
    }
    
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
        $this->id,
        $this->username,
        $this->password,
        $this->salt,
        $this->isActive,    
        $this->roles
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize( $serialized)
    {
        list(
        $this->id,
        $this->username,
        $this->password,
        $this->salt,
        $this->isActive,        
        $this->roles,
        ) = unserialize($serialized);
    }


    /**
     * Set isActive
     *
     * @param integer $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return integer
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set adresseIp
     *
     * @param string $adresseIp
     *
     * @return User
     */
    public function setAdresseIp($adresseIp)
    {
        $this->adresseIp = $adresseIp;

        return $this;
    }

    /**
     * Get adresseIp
     *
     * @return string
     */
    public function getAdresseIp()
    {
        return $this->adresseIp;
    }

    /**
     * Set nomMedia
     *
     * @param string $nomMedia
     *
     * @return User
     */
    public function setNomMedia($nomMedia)
    {
        $this->nomMedia = $nomMedia;

        return $this;
    }

    /**
     * Get nomMedia
     *
     * @return string
     */
    public function getNomMedia()
    {
        return $this->nomMedia;
    }
    
    /**
     * Set mediaFile
     * 
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $media
     */
     public function setMediafile($media)
    {
	$this->mediaFile = $media;
		 
	if ($media instanceof UploadedFile)
        {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdatedAt();		
        }	
     }
	 
        /**
	 * Get mediaFile
	 * 
	 * @return File
	 */
	public function getMediaFile()
	{
	  return $this->mediaFile;
	} 

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime("now");

        return $this;
    }
    

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add advert
     *
     * @param \Knarf\PlatformBundle\Entity\Advert $advert
     *
     * @return User
     */
    public function addAdvert(\Knarf\PlatformBundle\Entity\Advert $advert)
    {
        $this->adverts[] = $advert;

        return $this;
    }

    /**
     * Remove advert
     *
     * @param \Knarf\PlatformBundle\Entity\Advert $advert
     */
    public function removeAdvert(\Knarf\PlatformBundle\Entity\Advert $advert)
    {
        $this->adverts->removeElement($advert);
    }

    /**
     * Get adverts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdverts()
    {
        return $this->adverts;
    }

    /**
     * Add commentaire
     *
     * @param \Knarf\PlatformBundle\Entity\Commentaire $commentaire
     *
     * @return User
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
}
