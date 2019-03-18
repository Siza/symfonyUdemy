<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le modèle ne peut être vide")
     */
    private $model;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le prix ne peut être vide")
     * @Assert\LessThan(
     *  value = 100000, message="Maximum 100000"
     * )
     * @Assert\GreaterThan(
     *  value = 100, message="Minimum 100"
     * )
     */
    private $price;

    /**
    * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"})
    */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Keyword", mappedBy="car", cascade={"persist", "remove"})
     * @Assert\NotBlank(message="Le mot clé ne peut être vide")
     */
    private $keywords;

    public function __construct()
    {
        $this->keywords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return Collection|Keyword[]
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): self
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords[] = $keyword;
            $keyword->setCar($this);
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): self
    {
        if ($this->keywords->contains($keyword)) {
            $this->keywords->removeElement($keyword);
            // set the owning side to null (unless already changed)
            if ($keyword->getCar() === $this) {
                $keyword->setCar(null);
            }
        }

        return $this;
    }
}
