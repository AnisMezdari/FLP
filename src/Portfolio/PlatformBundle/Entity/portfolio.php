<?php

namespace Portfolio\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * portfolio
 *
 * @ORM\Table(name="portfolio")
 * @ORM\Entity(repositoryClass="Portfolio\PlatformBundle\Repository\portfolioRepository")
 */
class portfolio
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
    * @ORM\ManyToOne(targetEntity="Portfolio\PlatformBundle\Entity\categorie", cascade={"persist"})
    */
    private $categorie;

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
     * @return portfolio
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
     * Set categorie
     *
     * @param categorie $categorie
     *
     * @return portfolio
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

}

