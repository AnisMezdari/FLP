<?php

namespace Tarif\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * tarif
 *
 * @ORM\Table(name="tarif")
 * @ORM\Entity(repositoryClass="Tarif\PlatformBundle\Repository\tarifRepository")
 */
class tarif
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
     * @ORM\Column(name="texte", type="string", length=255, nullable=true)
     */
    private $texte;

    /**
     * @var string
     *
     * @ORM\Column(name="urlimage", type="string", length=255)
     */
    private $urlimage;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="string", length=255,nullable=true)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="fulltexte", type="text")
     */
    private $fullTexte;

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
     * Set texte
     *
     * @param string $texte
     *
     * @return tarif
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set urlimage
     *
     * @param string $urlimage
     *
     * @return tarif
     */
    public function setUrlimage($urlimage)
    {
        $this->urlimage = $urlimage;

        return $this;
    }

    /**
     * Get urlimage
     *
     * @return string
     */
    public function getUrlimage()
    {
        return $this->urlimage;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return tarif
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set fullTexte
     *
     * @param string $fullTexte
     *
     * @return fullTexte
     */
    public function setFullTexte($fullTexte)
    {
        $this->fullTexte = $fullTexte;

        return $this;
    }

    /**
     * Get fullTexte
     *
     * @return string
     */
    public function getFullTexte()
    {
        return $this->fullTexte;
    }
}
