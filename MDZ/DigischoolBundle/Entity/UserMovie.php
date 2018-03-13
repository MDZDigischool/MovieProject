<?php

namespace MDZ\DigischoolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserMovie
 *
 * @ORM\Table(name="user_movie")
 * @ORM\Entity(repositoryClass="MDZ\DigischoolBundle\Repository\UserMovieRepository")
 */
class UserMovie
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
	* @ORM\ManyToOne(targetEntity="MDZ\DigischoolBundle\Entity\User")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $user;

	/**
	* @ORM\ManyToOne(targetEntity="MDZ\DigischoolBundle\Entity\Movie")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $movie;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
