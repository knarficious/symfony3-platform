<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Knarf\UserBundle\Entity\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Knarf\CoreBundle\Validator\Constraints as CoreAssert;
use Knarf\UserBundle\Validator\Constraints as KnarfAssert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Description of Profil
 *
 * @author franck
 * @Vich\Uploadable 
 * 
 */
class Profile 
{
    /**
     * @var UserInterface
     */
    private $user;
    
    /**
     * @Assert\NotBlank(message = "registration.email.notblank")
     * @Assert\Email()
     * @CoreAssert\UniqueAttribute(
     *      repository="Knarf\UserBundle\Entity\User",
     *      property="email"
     * )
     * @KnarfAssert\EmailBlackList()
     */
    private $email;
    
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
     */
    private $nomMedia;
    
    //private $updatedAt;
    
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    
    public function getUser()
    {
        return $this->user;
    }    

    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email= $email;
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

    
    
}
