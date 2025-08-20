<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\UniqueConstraint(columns: ['name'] )]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Wish>
     */
    #[ORM\ManyToMany(targetEntity: Wish::class, inversedBy: 'categories')]
    private Collection $wish;

    public function __construct()
    {
        $this->wish = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Wish>
     */
    public function getWish(): Collection
    {
        return $this->wish;
    }


    public function addWish(Wish $wish): static
    {
        if (!$this->wish->contains($wish)) {
            $this->wish->add($wish);
        }

        return $this;
    }

    public function removeWish(Wish $wish): static
    {
        $this->wish->removeElement($wish);
        $wish->removeCategory($this);

        return $this;
    }

}
