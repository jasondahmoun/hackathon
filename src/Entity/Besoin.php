<?php

namespace App\Entity;

use App\Repository\BesoinRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BesoinRepository::class)]
class Besoin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'besoins')]
    private ?Chantier $chantier = null;

    #[ORM\Column(length: 50)]
    private ?string $type_poste = null;

    #[ORM\Column]
    private ?int $nombre_requis = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChantier(): ?Chantier
    {
        return $this->chantier;
    }

    public function setChantier(?Chantier $chantier): static
    {
        $this->chantier = $chantier;

        return $this;
    }

    public function getTypePoste(): ?string
    {
        return $this->type_poste;
    }

    public function setTypePoste(string $type_poste): static
    {
        $this->type_poste = $type_poste;

        return $this;
    }

    public function getNombreRequis(): ?int
    {
        return $this->nombre_requis;
    }

    public function setNombreRequis(int $nombre_requis): static
    {
        $this->nombre_requis = $nombre_requis;

        return $this;
    }
}
