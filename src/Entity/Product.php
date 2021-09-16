<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner le nom du produit")
     * @Assert\Length(min=3, minMessage="Le nom du produit doit faire au moins 3 caractères")
     * 
     *
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez renseigner le prix du produit")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message="Veuillez renseigner une URL valide pour la photo")
     * @Assert\NotBlank(message="Veuillez renseigner l'URL de la photo")
     */
    private $mainPicture;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez renseigner la description courte du produit")
     * @Assert\Length(min=20, minMessage="La description courte doit faire au moins 20 caractères")
     */
    private $shortDecription;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseItem::class, mappedBy="product")
     */
    private $purchaseItems;

    public function __construct()
    {
        $this->purchaseItems = new ArrayCollection();
    }

    // /**
    //  * @ORM\ManyToMany(targetEntity=Purchase::class, mappedBy="products")
    //  */
    // private $purchases;

    // public function __construct()
    // {
    //     $this->purchases = new ArrayCollection();
    // }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUppercaseName(): string
    {
        return strtoupper($this->name);
    }


    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(?string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getShortDecription(): ?string
    {
        return $this->shortDecription;
    }

    public function setShortDecription(?string $shortDecription): self
    {
        $this->shortDecription = $shortDecription;

        return $this;
    }

    // /**
    //  * @return Collection|Purchase[]
    //  */
    // public function getPurchases(): Collection
    // {
    //     return $this->purchases;
    // }

    // public function addPurchase(Purchase $purchase): self
    // {
    //     if (!$this->purchases->contains($purchase)) {
    //         $this->purchases[] = $purchase;
    //         $purchase->addProduct($this);
    //     }

    //     return $this;
    // }

    // public function removePurchase(Purchase $purchase): self
    // {
    //     if ($this->purchases->removeElement($purchase)) {
    //         $purchase->removeProduct($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection|PurchaseItem[]
     */
    public function getPurchaseItems(): Collection
    {
        return $this->purchaseItems;
    }

    public function addPurchaseItem(PurchaseItem $purchaseItem): self
    {
        if (!$this->purchaseItems->contains($purchaseItem)) {
            $this->purchaseItems[] = $purchaseItem;
            $purchaseItem->setProduct($this);
        }

        return $this;
    }

    public function removePurchaseItem(PurchaseItem $purchaseItem): self
    {
        if ($this->purchaseItems->removeElement($purchaseItem)) {
            // set the owning side to null (unless already changed)
            if ($purchaseItem->getProduct() === $this) {
                $purchaseItem->setProduct(null);
            }
        }

        return $this;
    }
}
