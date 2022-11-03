<?php

namespace App\Entity;

use App\Repository\FranchiseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FranchiseRepository::class)]
class Franchise
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private $id;

    #[ORM\Column(length: 60)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\OneToOne(inversedBy: 'franchise', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'franchise', cascade: ['persist', 'remove'])]
    private ?Contract $contract = null;

    #[ORM\OneToMany(mappedBy: 'franchise', targetEntity: Gym::class, orphanRemoval: true)]
    private Collection $gym;

    public function __construct()
    {
        $this->gym = new ArrayCollection();
    }

    public function getId(): ?Uuid
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(Contract $contract): self
    {
        // set the owning side of the relation if necessary
        if ($contract->getFranchise() !== $this) {
            $contract->setFranchise($this);
        }

        $this->contract = $contract;

        return $this;
    }

    /**
     * @return Collection<int, Gym>
     */
    public function getgym(): Collection
    {
        return $this->gym;
    }

    public function addGym(Gym $gym): self
    {
        if (!$this->gym->contains($gym)) {
            $this->gym->add($gym);
            $gym->setFranchise($this);
        }

        return $this;
    }

    public function removeGym(Gym $gym): self
    {
        if ($this->gym->removeElement($gym)) {
            // set the owning side to null (unless already changed)
            if ($gym->getFranchise() === $this) {
                $gym->setFranchise(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
