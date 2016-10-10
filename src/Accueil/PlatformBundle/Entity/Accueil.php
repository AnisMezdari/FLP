<?php

namespace Accueil\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Accueil
 *
 * @ORM\Table(name="accueil")
 * @ORM\Entity(repositoryClass="Accueil\PlatformBundle\Repository\AccueilRepository")
 */
class Accueil
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
     * @ORM\Column(name="urlImage", type="string", length=255, unique=false)
     */
    private $urlImage;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

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
     * @return Accueil
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
     * Set position
     *
     * @param string $position
     *
     * @return Accueil
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }



}

