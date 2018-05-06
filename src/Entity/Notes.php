<?php


namespace Src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notes
 *
 * @ORM\Table(name="notes")
 * @ORM\Entity
 */
class Notes
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
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="content", type="string", length=200, nullable=true)
     */
    private $content;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="private", type="boolean", nullable=true)
     */
    private $private;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tag1", type="string", length=20, nullable=true)
     */
    private $tag1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tag2", type="string", length=20, nullable=true)
     */
    private $tag2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tag3", type="string", length=20, nullable=true)
     */
    private $tag3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tag4", type="string", length=20, nullable=true)
     */
    private $tag4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="book", type="string", length=50, nullable=true)
     */
    private $book;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="createData", type="date", nullable=true)
     */
    private $createData;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastModificationData", type="date", nullable=true)
     */
    private $lastmodificationdata;

    /**
     * @var Usuari
     * @ORM\ManyToOne(targetEntity="Src\Entity\Usuari", inversedBy="notes")
     * @ORM\JoinColumn(name="id_usuari", referencedColumnName="id")
     */
    private $user;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return null|string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param null|string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return bool|null
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @param bool|null $private
     */
    public function setPrivate($private)
    {
        $this->private = $private;
    }

    /**
     * @return null|string
     */
    public function getTag1()
    {
        return $this->tag1;
    }

    /**
     * @param null|string $tag1
     */
    public function setTag1($tag1)
    {
        $this->tag1 = $tag1;
    }

    /**
     * @return null|string
     */
    public function getTag2()
    {
        return $this->tag2;
    }

    /**
     * @param null|string $tag2
     */
    public function setTag2($tag2)
    {
        $this->tag2 = $tag2;
    }

    /**
     * @return null|string
     */
    public function getTag3()
    {
        return $this->tag3;
    }

    /**
     * @param null|string $tag3
     */
    public function setTag3($tag3)
    {
        $this->tag3 = $tag3;
    }

    /**
     * @return null|string
     */
    public function getTag4()
    {
        return $this->tag4;
    }

    /**
     * @param null|string $tag4
     */
    public function setTag4($tag4)
    {
        $this->tag4 = $tag4;
    }

    /**
     * @return null|string
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param null|string $book
     */
    public function setBook($book)
    {
        $this->book = $book;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreateData()
    {
        return $this->createData;
    }

    /**
     * @param \DateTime|null $createData
     */
    public function setCreateData($createData)
    {
        $this->createData = $createData;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastmodificationdata()
    {
        return $this->lastmodificationdata;
    }

    /**
     * @param \DateTime|null $lastmodificationdata
     */
    public function setLastmodificationdata($lastmodificationdata)
    {
        $this->lastmodificationdata = $lastmodificationdata;
    }




}
