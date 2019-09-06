<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;


/**
 * Description of Avatar
 *
 * @author franck
 * 
 * @Vich\Uploadable
 * 
 * @ORM\Table(name="avatar")
 * @ORM\Entity(repositoryClass="Knarf\UserBundle\Repository\AvatarRepository")
 */
class Avatar 
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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Assert\File(
     * 		maxSize="10M",
     * 		mimeTypes={"image/png", "image/jpeg", "image/gif"})
     * @Vich\UploadableField(mapping="upload_avatar", fileNameProperty="nomMedia")
     * 
     * @var File
     */
    private $mediaFile;

    /**
     * @ORM\Column(name="nom_media", type="string", length=255)
     * @var string
     */
    private $nomMedia;
    
    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    
    /// ASSOCIATIONS ///
    
    /**
     * @ORM\OneToOne(targetEntity="Knarf\UserBundle\Entity\App_User")
     * @ORM\JoinColumn(name="app_user", nullable=true, onDelete="SET NULL")
     */
    private $user;
    
    public function __construct($thisUser)
    {
        $this->date = new \DateTime();
        $this->user = $thisUser;
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
     * Get user
     * 
     * @return \Knarf\UserBundle\Entity\App_User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Set user
     * 
     * @param \Knarf\UserBundle\Entity\App_User
     * 
     * @return Avatar 
     */
    public function setUser(\Knarf\UserBundle\Entity\App_User $user){
        
        $this->user = $user;
    }
    
    /**
     * Set nomMedia
     *
     * @param string $nomMedia
     *
     * @return Avatar
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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $mediaFile
     */
     public function setMediafile(File $mediaFile = null)
    {
	$this->mediaFile = $mediaFile;
		 
	if ($mediaFile instanceof UploadedFile)
        {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdate(new \DateTime('now'));		
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
     * Set date
     * 
     * @param \DatTime date
     * 
     * @return Avatar
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
    
    /**
     * Get date
     * 
     * @return update
     */
    public function getDate()
    {
        return $this->date;
    }
    
    private $path;

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads';
    }
    
    public function __toString() 
    {
        return $this->getNomMedia();
    }
    
    

    
}
