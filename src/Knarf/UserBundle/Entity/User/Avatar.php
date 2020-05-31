<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\UserBundle\Entity\User;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * Description of Avatar
 *
 * @author franck
 * 
 * @Vich\Uploadable 
 */
class Avatar 
{
    /**
     * @var user UserInterface
     */
    private $user;
    
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
     * @var string
     */
    private $nomMedia;
    
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    
    public function getUser()
    {
        return $this->user;
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
            $this->setUpdatedAt(new \DateTime('now'));		
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
