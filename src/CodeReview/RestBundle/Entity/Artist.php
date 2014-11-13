<?php

namespace CodeReview\RestBundle\Entity;

use CodeReview\RestBundle\Model\ArtistInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CodeReview\RestBundle\Repository\ArtistRepository")
 * @ORM\Table(name="artist")
 */
class Artist implements ArtistInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Artist
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}