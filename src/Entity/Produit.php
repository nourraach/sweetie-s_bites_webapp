<?php

namespace App\Entity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[Vich\Uploadable]
class Produit {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column]
    private string $nomProduit;

    #[ORM\Column(type: 'float')]
    private float $prix;
    
    

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'produits')]
    private Categorie $categorie;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'produits')]
    private ?Commande $commande= null;
    #[Vich\UploadableField(mapping: 'product_image', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

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
     * Get the value of nomProduit
     *
     * @return string
     */
    public function getNomProduit(): string
    {
        return $this->nomProduit;
    }

    /**
     * Set the value of nomProduit
     *
     * @param string $nomProduit
     *
     * @return self
     */
    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    /**
     * Get the value of prix
     *
     * @return float
     */
    public function getPrix(): float
    {
        return $this->prix;
    }

    /**
     * Set the value of prix
     *
     * @param float $prix
     *
     * @return self
     */
    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get the value of categorie
     *
     * @return Categorie
     */
    public function getCategorie(): Categorie
    {
        return $this->categorie;
    }

    /**
     * Set the value of categorie
     *
     * @param Categorie $categorie
     *
     * @return self
     */
    public function setCategorie(Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    public function getUpdatedAt(): ?\DateTimeInterface
{
    return $this->updatedAt;
}

// Setter
public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
{
    $this->updatedAt = $updatedAt;
    return $this;
}
public function getCommande():? Commande
{
    return $this->commande;
}

/**
 * Set the value of commande
 *
 * @param Commande $commande
 *
 * @return self
 */
public function setCommande(?Commande $commande): self
{
    $this->commande = $commande;
    return $this;
}
}