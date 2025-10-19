<?php

namespace App\Entity;

use App\Repository\EditorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: EditorRepository::class)]
class Editor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'éditeur ne peut pas être vide")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom doit contenir au moins 2 caractères",
        maxMessage: "Le nom ne peut pas dépasser 255 caractères"
    )]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Le pays ne peut pas être vide")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: "Le pays doit contenir au moins 2 caractères",
        maxMessage: "Le pays ne peut pas dépasser 20 caractères"
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-]+$/",
        message: "Le pays ne peut contenir que des lettres, espaces et tirets"
    )]
    private ?string $country = null;

    #[ORM\ManyToMany(targetEntity: VideoGame::class, mappedBy: 'editors')]
    private Collection $videoGames;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }
}
