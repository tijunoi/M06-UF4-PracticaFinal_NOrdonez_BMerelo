<?php
/**
 * Created by PhpStorm.
 * User: nil
 * Date: 5/5/18
 * Time: 0:16
 */

namespace Src\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Src\Auth\UnauthorizedException;

/**
 * Usuari
 *
 * @ORM\Table(name="usuaris")
 * @ORM\Entity
 */
class Usuari
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;


    /**
     * @var string|null
     *
     * @ORM\Column(name="clau",type="string", length=255, nullable=true)
     */
    private $clau;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_login",type="datetime", nullable=true)
     */
    private $lastLogin;


    /**
     * @ORM\OneToMany(targetEntity="Src\Entity\Notes", mappedBy="user")
     */
    private $notes;

    /**
     * Usuari constructor.
     */
    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return null|string
     */
    public function getClau()
    {
        return $this->clau;
    }

    /**
     * @param null|string $clau
     */
    public function setClau($clau)
    {
        $this->clau = $clau;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }






}