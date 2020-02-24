<?php

namespace Knarf\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//use Knarf\UserBundle\Entity\Interfaces\AppUserI  nterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User
 *
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="Knarf\UserBundle\Repository\UserRepository")
 * 
 * @UniqueEntity(fields="username", message="Ce pseudo existe déjà!")
 * @UniqueEntity(fields="email", message="Cet email existe déjà!")
 * @Vich\Uploadable
 */
class App_User implements AdvancedUserInterface, \Serializable, EquatableInterface
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
     * @Assert\NotBlank(message="Give us at least 3 characters")
     * @Assert\Length(min=3, minMessage="Give us at least 3 characters!")
     */
    private $username;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
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
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="last_time_connect", type="datetime", nullable=true)
     */
    private $lastTimeConnect;
    
    /**
     * 
     * @ORM\Column(name="adresse_ip", type="string", nullable=true)
     */
    private $adresseIp;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array", nullable=false)
     */
    private $roles = array();
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    
    /**
     * @ORM\Column(name="api_key", type="string", unique=true, nullable=true)
     */
    private $apiKey;
    
    /**
     * @ORM\Column(name="confirmation_token", type="string", length=255, nullable=true)
     */
    private $confirmationToken = null;
    
    /**
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/",
     *      message="Use 1 upper case letter, 1 lower case letter, and 1 number"
     * )
     */
    private $plainPassword;
    
//    /**
//     * NOTE: This is not a mapped field of entity metadata, just a simple property.
//     * 
//     * @Assert\File(
//     * 		maxSize="10M",
//     * 		mimeTypes={"image/png", "image/jpeg", "image/gif"})
//     * @Vich\UploadableField(mapping="upload_avatar", fileNameProperty="nomMedia")
//     * 
//     * @var File
//     */
//    private $mediaFile;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="nom_media", type="string", length=255, nullable=true)
//     */
//    private $nomMedia;
         
    /**
     * @var bool
     *
     * @ORM\Column(name="is_already_requested", type="boolean")
     */
    private $isAlreadyRequested = false;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="cgv_read", type="boolean", nullable=true)
     */
    private $cgvRead;
    
    /**
     * @Gedmo\Slug(fields={"username"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    
    // === ASSOCIATIONS ===
    
    /**
     * @ORM\OneToMany(targetEntity="Knarf\PlatformBundle\Entity\Advert", cascade={"persist", "remove"}, mappedBy="user")
     */
    private $adverts;
    
    /**
     * @ORM\OneToMany(targetEntity="Knarf\PlatformBundle\Entity\Commentaire", cascade={"persist", "remove"}, mappedBy="user")
     */
    private $commentaires;
    
    /**
     * @ORM\OneToOne(targetEntity="Knarf\UserBundle\Entity\Avatar", cascade={"remove"})
     * @ORM\JoinColumn(name="avatar", nullable=true, onDelete="SET NULL")
     */
    private $avatar;
    

//    public function __construct($username, $password, $salt, array $roles)
//    {
//        //parent::__construct();
//        
//        $this->username = $username;
//        $this->password = $password;
//        $this->salt = $salt;
//        $this->roles = $roles;
//        $this->adverts = new ArrayCollection();
//        $this->commentaires = new ArrayCollection();
//        
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
     * Set username
     *
     * @param string $username
     *
     * @return App_User
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
     * @return App_User
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
     * @return boolean
     */
    public function isCgvRead()
    {
        return $this->cgvRead;
    }
    
    /**
     * @param boolean $cgvRead
     */
    public function setCgvRead($cgvRead)
    {
        $this->cgvRead = $cgvRead;
    }
    
    /**
     * @param PasswordEncoderInterface $encoder
     */
    public function encodePassword(PasswordEncoderInterface $encoder)
    {
        if ($this->plainPassword) {
            $this->salt = sha1(uniqid(mt_rand()));
            $this->password = $encoder->encodePassword(
                $this->plainPassword, $this->salt
            );
            $this->eraseCredentials();
        }
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return App_User
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
     * @return App_User
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    
    public function eraseCredentials()
    {
	$this->setPlainPassword(null);
    }
    
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
        $this->id,
        $this->username,
        $this->email,
        $this->password,
        $this->salt,
        $this->isActive    
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
        $this->email,
        $this->password,
        $this->salt,
        $this->isActive
        ) = unserialize($serialized);
    }


    /**
     * Set isActive
     *
     * @param integer $isActive
     *
     * @return App_User
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
     * @return App_User
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
     * @return App_User
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
     * @return App_User
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
//    public function setNomMedia($nomMedia)
//    {
//        $this->nomMedia = $nomMedia;
//
//        return $this;
//    }
//
//    /**
//     * Get nomMedia
//     *
//     * @return string
//     */
//    public function getNomMedia()
//    {
//        return $this->nomMedia;
//    }
//    
//    /**
//     * Set mediaFile
//     * 
//     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $media
//     */
//     public function setMediafile($media)
//    {
//	$this->mediaFile = $media;
//		 
//	if ($media instanceof UploadedFile)
//        {
//            // It is required that at least one field changes if you are using doctrine
//            // otherwise the event listeners won't be called and the file is lost
//            $this->setUpdatedAt();		
//        }	
//     }
//	 
//        /**
//	 * Get mediaFile
//	 * 
//	 * @return File
//	 */
//	public function getMediaFile()
//	{
//	  return $this->mediaFile;
//	} 

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return App_User
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
     * @return App_User
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
     * @return App_User
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
     * Set lastTimeConnect
     *
     * @param \DateTime $lastTimeConnect
     *
     * @return App_User
     */
    public function setLastTimeConnect(\DateTime $lastTimeConnect)
    {
        $this->lastTimeConnect = $lastTimeConnect;

        return $this;
    }

    /**
     * Get lastTimeConnect
     *
     * @return \DateTime
     */
    public function getLastTimeConnect()
    {
        return $this->lastTimeConnect;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     *
     * @return App_User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }




    /**
     * Set confirmationToken
     *
     * @param string $confirmationToken
     *
     * @return App_User
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * Get confirmationToken
     *
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }
    
      public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * Set isAlreadyRequested
     *
     * @param boolean $isAlreadyRequested
     *
     * @return App_User
     */
    public function setIsAlreadyRequested($isAlreadyRequested)
    {
        $this->isAlreadyRequested = $isAlreadyRequested;

        return $this;
    }

    /**
     * Get isAlreadyRequested
     *
     * @return boolean
     */
    public function getIsAlreadyRequested()
    {
        return $this->isAlreadyRequested;
    }

    /**
     * Get cgvRead
     *
     * @return boolean
     */
    public function getCgvRead()
    {
        return $this->cgvRead;
    }

    public function isEqualTo(\Symfony\Component\Security\Core\User\UserInterface $user) {
        
        if (!$user instanceof App_User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
        
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->adverts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return App_User
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


    /**
     * Set avatar
     * 
     * @return \Knarf\UserBundle\Entity\Avatar
     */
    public function setAvatar(\Knarf\UserBundle\Entity\Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Knarf\UserBundle\Entity\Avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
    
    
}
