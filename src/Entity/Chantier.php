<?php

namespace App\Entity;

use App\Repository\ChantierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChantierRepository::class)]
class Chantier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_fin = null;

    /**
     * @var Collection<int, Employee>
     */
    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'chantiers')]
    private Collection $employes_affectes;

    /**
     * @var Collection<int, Besoin>
     */
    #[ORM\OneToMany(targetEntity: Besoin::class, mappedBy: 'chantier')]
    private Collection $besoins;

    /**
     * @var Collection<int, Employee>
     */
    #[ORM\OneToMany(targetEntity: Employee::class, mappedBy: 'chantier_actuel')]
    private Collection $employees;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'chantier')]
    private Collection $affectations;

    public function __construct()
    {
        $this->employes_affectes = new ArrayCollection();
        $this->besoins = new ArrayCollection();
        $this->employees = new ArrayCollection();
        $this->affectations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployesAffectes(): Collection
    {
        return $this->employes_affectes;
    }

    public function __toString(): string
    {
        return $this->nom;
    }

    public function addEmployesAffecte(Employee $employesAffecte): static
    {
        if (!$this->employes_affectes->contains($employesAffecte)) {
            $this->employes_affectes->add($employesAffecte);
        }

        return $this;
    }

    public function removeEmployesAffecte(Employee $employesAffecte): static
    {
        $this->employes_affectes->removeElement($employesAffecte);

        return $this;
    }

    /**
     * @return Collection<int, Besoin>
     */
    public function getBesoins(): Collection
    {
        return $this->besoins;
    }

    public function addBesoin(Besoin $besoin): static
    {
        if (!$this->besoins->contains($besoin)) {
            $this->besoins->add($besoin);
            $besoin->setChantier($this);
        }

        return $this;
    }

    public function removeBesoin(Besoin $besoin): static
    {
        if ($this->besoins->removeElement($besoin)) {
            // set the owning side to null (unless already changed)
            if ($besoin->getChantier() === $this) {
                $besoin->setChantier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): static
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
            $employee->setChantierActuel($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): static
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getChantierActuel() === $this) {
                $employee->setChantierActuel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): static
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
            $affectation->setChantier($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): static
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getChantier() === $this) {
                $affectation->setChantier(null);
            }
        }

        return $this;
    }
}
