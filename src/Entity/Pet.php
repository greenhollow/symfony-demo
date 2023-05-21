<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PetRepository::class)]
#[ApiResource]
class Pet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $date_of_birth = null;

    #[ORM\ManyToMany(targetEntity: Human::class, mappedBy: 'pets')]
    private Collection $humans;

    public function __construct()
    {
        $this->humans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
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

    public function getDateOfBirth(): ?\DateTimeImmutable
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTimeImmutable $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    /**
     * @return Collection<int, Human>
     */
    public function getHumans(): Collection
    {
        return $this->humans;
    }

    public function addHuman(Human $human): self
    {
        if (!$this->humans->contains($human)) {
            $this->humans->add($human);
            $human->addPet($this);
        }

        return $this;
    }

    public function removeHuman(Human $human): self
    {
        if ($this->humans->removeElement($human)) {
            $human->removePet($this);
        }

        return $this;
    }
}
