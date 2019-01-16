<?php

namespace Knarf\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Knarf\CoreBundle\Validator\Constraints as CoreAssert;
use Knarf\UserBundle\Validator\Constraints as KnarfAssert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * Image
 * 
 * @author franck
 * @Vich\Uploadable
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Knarf\PlatformBundle\Repository\ImageRepository")
 */
class Image
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;
    
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
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="image")
     */
    private $user;



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
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
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
     *  Get mediaFile
     * 
     * @return File
    */
    public function getMediaFile()
    {
        return $this->mediaFile;
    }
    
    /**
     * Get user
     * @return \Knarf\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Image
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

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
     * Set user
     *
     * @param \Knarf\UserBundle\Entity\User $user
     *
     * @return Image
     */
    public function setUser(\Knarf\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }
}
