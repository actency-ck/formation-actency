<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $duDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    private $file;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="task", cascade={"persist", "remove"})
     */
    private $user;

    public function __construct($name = '')
    {
        $this->name = $name;
        $this->duDate = new \DateTime();
    }

    /**
     * @ORM\PreFlush()
     */
    public function callBeforeFlushAfterPersist()
    {

    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDuDate(): ?\DateTimeInterface
    {
        return $this->duDate;
    }

    public function setDuDate(\DateTimeInterface $duDate): self
    {
        $this->duDate = $duDate;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newTask = $user === null ? null : $this;
        if ($newTask !== $user->getTask()) {
            $user->setTask($newTask);
        }

        return $this;
    }

    // Update attributes with yours
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->name
            ) = unserialize($serialized);
    }
}
