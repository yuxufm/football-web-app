<?php

namespace App\Entity;

use App\Repository\PlayerTransfersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerTransfersRepository::class)]
#[ORM\HasLifecycleCallbacks]
class PlayerTransfers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Players $player = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teams $buying_team = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teams $selling_team = null;

    #[ORM\Column]
    private ?int $transfer_amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $transfer_date = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Players
    {
        return $this->player;
    }

    public function setPlayer(?Players $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getBuyingTeam(): ?Teams
    {
        return $this->buying_team;
    }

    public function setBuyingTeam(?Teams $buying_team): self
    {
        $this->buying_team = $buying_team;

        return $this;
    }

    public function getSellingTeam(): ?Teams
    {
        return $this->selling_team;
    }

    public function setSellingTeam(?Teams $selling_team): self
    {
        $this->selling_team = $selling_team;

        return $this;
    }

    public function getTransferAmount(): ?int
    {
        return $this->transfer_amount;
    }

    public function setTransferAmount(int $transfer_amount): self
    {
        $this->transfer_amount = $transfer_amount;

        return $this;
    }

    public function getTransferDate(): ?\DateTimeInterface
    {
        return $this->transfer_date;
    }

    public function setTransferDate(\DateTimeInterface $transfer_date): self
    {
        $this->transfer_date = $transfer_date;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at !== null ? $this->created_at->format('Y-m-d H:i:s') : null;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at !== null ? $this->updated_at->format('Y-m-d H:i:s') : null;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }
}
