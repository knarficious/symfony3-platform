<?php

namespace Knarf\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="Knarf\PlatformBundle\Repository\MediaRepository") * 
 * @Vich\Uploadable
 */
class Media
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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Assert\File(
     * 		maxSize="10M",
     *          maxSizeMessage="Le fichier est trop volumineux: {{size }}. La limite est {{ limit }}",
     * 		mimeTypes={"image/png",
     *                     "image/jpeg",
     *                     "image/gif",
     *                     "image/svg+xml",
     *                     "audio/mpeg",
     *                     "audio/ogg",
     *                     "video/mp4",
     *                     "video/avi",
     *                     "video/x-msvideo"},
     *          mimeTypesMessage="Ce type de fichier n'est pas autorisé: les types autorisés sont {{ types }}",
     *          uploadErrorMessage="Le fichier ne peut pas etre téléchargé :-(")
     * @Vich\UploadableField(mapping="upload_media", fileNameProperty="nomMedia")
     * 
     * @var File
     */
    private $mediaFile;

     /**
     * @var string
     * @ORM\Column(name="nom_media", type="string", length=255, nullable=false)
     */
    private $nomMedia;

        /// ASSOCIATIONS ///
//    
//    /**
//     * @ORM\OneToOne(targetEntity="Knarf\PlatformBundle\Entity\Advert")
//     * @ORM\JoinColumn(name="advert", nullable=true, onDelete="SET NULL")
//     */
//    private $advert;
    
//    public function __construct($thisadvert)
//    {
//        $this->date = new \DateTime();
//        $this->advert = $thisadvert;
//    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    
    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Media
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

//    /**
//     * Get advert
//     * 
//     * @return \KnarfplatformBundle\Entity\Advert
//     */
//    public function getAdvert()
//    {
//        return $this->advert;
//    }
//    
//    /**
//     * Set advert
//     * 
//     * @param \Knarf\PlatformBundle\Entity\Advert
//     * 
//     * @return Advert 
//     */
//    public function setAdvert(\Knarf\PlatformBundle\Entity\Advert $advert){
//        
//        $this->advert = $advert;
//    }
    
    /**
     * Set nomMedia
     *
     * @param string $nomMedia
     *
     * @return string
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
