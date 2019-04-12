<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

/**
 * @Assert\GroupSequenceProvider()
 */
class TaskOld implements GroupSequenceProviderInterface
{
    /**
     * @Assert\Length(min=2, minMessage="task.validator.min_length {{ limit }},
     *                       task.validator.min_length.value {{ value }}")
     */
    private $name;

    private $duDate;

    private $image;

    private $author;

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @Assert\IdenticalTo("top prio", groups={"Priority"}, message="task.priority.high")
     */
    private $priority;

    public function __construct()
    {
        $this->duDate = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     */
    public function setPriority($priority): void
    {
        $this->priority = $priority;
    }



    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDuDate(): ?\DateTimeInterface
    {
        return $this->duDate;
    }

    /**
     * @param mixed $duDate
     */
    public function setDuDate($duDate): void
    {
        $this->duDate = $duDate;
    }

    public function getGroupSequence()
    {
        $group = ["Task"];

        $date = new \DateTime();

        $currentMonth = $date->format('m');
        if($this->getDuDate()->format('m') == $currentMonth && $this->priority != 'top prio') {

            $group[] = "Priority";
        }

        return $group;
    }

}
