<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(type="integer")
     */
    private $parentId;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @ORM\Column(type="integer")
     */
    private $delayedTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $updatedTime;

    public function getId()
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

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getDelayedTime(): ?int
    {
        return $this->delayedTime;
    }

    public function setDelayedTime(int $delayedTime): self
    {
        $this->delayedTime = $delayedTime;

        return $this;
    }

    public function getCreatedTime(): ?int
    {
        return $this->createdTime;
    }

    public function setCreatedTime(int $createdTime): self
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    public function getUpdatedTime(): ?int
    {
        return $this->updatedTime;
    }

    public function setUpdatedTime(int $updatedTime): self
    {
        $this->updatedTime = $updatedTime;

        return $this;
    }
}
