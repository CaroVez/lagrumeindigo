<?php

namespace App\Entity;

use App\Repository\ContractRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ContractRepository::class)]
class Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $sendNewsletter = null;

    #[ORM\Column]
    private ?bool $teamPlanning = null;

    #[ORM\Column]
    private ?bool $sellDrinks = null;

    #[ORM\Column]
    private ?bool $promotion = null;

    #[ORM\Column]
    private ?bool $paymentSchedules = null;

    #[ORM\Column]
    private ?bool $statistics = null;

    #[ORM\OneToOne(inversedBy: 'contract', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Franchise $franchise = null;

    #[ORM\OneToOne(inversedBy: 'contract', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gym $gym = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function isSendNewsletter(): ?bool
    {
        return $this->sendNewsletter;
    }

    public function setSendNewsletter(bool $sendNewsletter): self
    {
        $this->sendNewsletter = $sendNewsletter;

        return $this;
    }

    public function isTeamPlanning(): ?bool
    {
        return $this->teamPlanning;
    }

    public function setTeamPlanning(bool $teamPlanning): self
    {
        $this->teamPlanning = $teamPlanning;

        return $this;
    }

    public function isSellDrinks(): ?bool
    {
        return $this->sellDrinks;
    }

    public function setSellDrinks(bool $sellDrinks): self
    {
        $this->sellDrinks = $sellDrinks;

        return $this;
    }

    public function isPromotion(): ?bool
    {
        return $this->promotion;
    }

    public function setPromotion(bool $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function isPaymentSchedules(): ?bool
    {
        return $this->paymentSchedules;
    }

    public function setPaymentSchedules(bool $paymentSchedules): self
    {
        $this->paymentSchedules = $paymentSchedules;

        return $this;
    }

    public function isStatistics(): ?bool
    {
        return $this->statistics;
    }

    public function setStatistics(bool $statistics): self
    {
        $this->statistics = $statistics;

        return $this;
    }

    public function getFranchise(): ?Franchise
    {
        return $this->franchise;
    }

    public function setFranchise(Franchise $franchise): self
    {
        $this->franchise = $franchise;

        return $this;
    }

    public function getGym(): ?Gym
    {
        return $this->gym;
    }

    public function setGym(Gym $gym): self
    {
        $this->gym = $gym;

        return $this;
    }
}
