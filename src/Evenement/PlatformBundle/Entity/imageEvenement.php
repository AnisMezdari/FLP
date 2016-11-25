<?php

namespace Evenement\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * imageEvenement
 *
 * @ORM\Table(name="image_evenement")
 * @ORM\Entity(repositoryClass="Evenement\PlatformBundle\Repository\imageEvenementRepository")
 */
class imageEvenement
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
     * @ORM\Column(name="urlImage", type="string", length=255)
     */
    private $urlImage;


    /**
    * @ORM\ManyToOne(targetEntity="Evenement\PlatformBundle\Entity\evenement")
    */
    public $evenement;

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
     * Set urlImage
     *
     * @param string $urlImage
     *
     * @return imageEvenement
     */
    public function setUrlImage($urlImage)
    {
        $this->urlImage = $urlImage;

        return $this;
    }

    /**
     * Get urlImage
     *
     * @return string
     */
    public function getUrlImage()
    {
        return $this->urlImage;
    }

     /**
     * Set evenement
     *
     * @param evenement $evenement
     *
     * @return evenement
     */
    public function setEvenement($evenement){
        $this->evenement = $evenement;

        return $this;
    }
}

