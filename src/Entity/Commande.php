<?php

namespace App\Entity;
use App\Repository\ProduitRepository;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\OneToMany(targetEntity: CommandeProduit::class, mappedBy: 'commande', orphanRemoval: true)]
private Collection $commandeProduits;

    #[ORM\Column(length: 255)]
    private ?string $numCommande = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
         $this->commandeProduits = new ArrayCollection();
    }

    // getters/setters ici

    /**
     * Get the value of id
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param ?int $id
     *
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of numCommande
     *
     * @return ?string
     */
    public function getNumCommande(): ?string
    {
        return $this->numCommande;
    }

    /**
     * Set the value of numCommande
     *
     * @param ?string $numCommande
     *
     * @return self
     */
    public function setNumCommande(?string $numCommande): self
    {
        $this->numCommande = $numCommande;

        return $this;
    }
    public function getProduits(): Collection
    {
        return $this->produits;
    }

public function addProduit(Produit $produit): self
{
    if (!$this->produits->contains($produit)) {
        $this->produits[] = $produit;
    }
    return $this;
}
public function addCommandeProduit(CommandeProduit $commandeProduit): self
{
    if (!$this->commandeProduits->contains($commandeProduit)) {
        $this->commandeProduits->add($commandeProduit);
        $commandeProduit->setCommande($this);
    }
    return $this;
}

public function removeCommandeProduit(CommandeProduit $commandeProduit): self
{
    if ($this->commandeProduits->removeElement($commandeProduit)) {
        if ($commandeProduit->getCommande() === $this) {
            $commandeProduit->setCommande(null);
        }
    }
    return $this;
}

}
