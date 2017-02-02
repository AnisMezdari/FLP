<?php

namespace User\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * imageUser
 *
 * @ORM\Table(name="image_user")
 * @ORM\Entity(repositoryClass="User\PlatformBundle\Repository\imageUserRepository")
 */
class imageUser
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
    * @ORM\ManyToOne(targetEntity="User\PlatformBundle\Entity\user")
    */
    public $user;


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
     * @return imageUser
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


    public function setUser($user){
      $this->user = $user;
      return $this;
    }

    public function getUser(){
      return $this->user;
    }
}
