<?php

namespace Knarf\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Rubrique
 *
 * @ORM\Table(name="rubrique")
 * @ORM\Entity(repositoryClass="Knarf\PlatformBundle\Repository\RubriqueRepository")
 * @Vich\Uploadable
 */
class Rubrique
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
     * @ORM\Column(name="intitule", type="string", length=255, unique=true)
     */
    private $intitule;
    
    /**
     * @Gedmo\Slug(fields={"intitule"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;
    
    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="updateAt", type="datetime")
     */
    private $updateAt;
    
        /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Assert\File(
     * 		maxSize="1M",
     * 		mimeTypes={"image/png",
     *                     "image/jpeg",
     *                     "image/gif",
     *                     "image/svg+xml"},
     *                     uploadErrorMessage="Le fichier ne peut pas etre téléchargé :-(")
     * @Vich\UploadableField(mapping="upload_media", fileNameProperty="image")
     * 
     * @var File
     */
    private $mediaFile;
    
        // === ASSOCIATIONS ===
    
    /**
     * @ORM\OneToMany(targetEntity="Knarf\PlatformBundle\Entity\Advert", mappedBy="rubrique", cascade={"persist", "remove"})
     */
    private $adverts;


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
     * Set intitule
     *
     * @param string $intitule
     *
     * @return Rubrique
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->adverts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add advert
     *
     * @param \Knarf\PlatformBundle\Entity\Advert $advert
     *
     * @return Rubrique
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Rubrique
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
     * Set image
     *
     * @param string $image
     *
     * @return Rubrique
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Set mediaFile
     * 
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $mediaFile
     */
     public function setMediaFile($mediaFile)
    {
	$this->mediaFile = $mediaFile;
		 
	if ($mediaFile instanceof UploadedFile)
        {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdateAt(new \DateTime('now'));		
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
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return Rubrique
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }
}
