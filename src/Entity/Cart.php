<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\FloatNode;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="cart", orphanRemoval=true, cascade={"persist"})
     */
    private $cartitems;

    #/**
    # * @ORM\Column(type="uuid", nullable=true)
    # */
    #private $uuid;

    public function __construct()
    {
        $this->cartitems = new ArrayCollection();
        #$this->uuid = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|CartItem[]
     */
    public function getCartitems(): Collection
    {
        return $this->cartitems;
    }

    public function addCartitem(CartItem $cartitem): CartItem
    {
        foreach ($this->cartitems as $item){
            /**
             * @var CartItem $item
             */
            if($item->getProduct()->getId() == $cartitem->getProduct()->getId()){
                $item->setQuantity($item->getQuantity() + $cartitem->getQuantity());
                return $item;
            }
        }

        $this->cartitems[] = $cartitem;

        return $cartitem;
    }

    public function removeCartitem(Product $product)
    {
        /*if ($this->cartitems->removeElement($cartitem)) {
            // set the owning side to null (unless already changed)
            if ($cartitem->getCart() === $this) {
                $cartitem->setCart(null);
            }
        }*/

        foreach ($this->cartitems as $item){
            /**
             * @var CartItem $item
             */
            if($item->getProduct()->getId() == $product->getId()){

                $this->cartitems->removeElement($item);
                return $this;
            }
        }


        return $this;
    }

    public function getTotalAmount()
    {
        $totalAmount = 0;
        foreach($this->cartitems as $cartitem){
            /**
             * @var $cartitem CartItem
             */
            $totalAmount += $cartitem->getQuantity() * $cartitem->getProduct()->getPrice();
        }

        return $totalAmount;

    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
