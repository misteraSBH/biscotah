<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
    private $cartItems;

    /**
     * @ORM\Column(type="uuid", nullable=true)
     */
    private $uuid;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
        $this->uuid = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|CartItem[]
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): CartItem
    {
        #dump($this->cartItems);
        foreach ($this->cartItems as $item){

            /**
             * @var CartItem $item
             */
            if($item->getProduct()->getId() == $cartItem->getProduct()->getId() && empty(array_diff($item->getOptions(),$cartItem->getOptions())) ){
                $item->setQuantity($item->getQuantity() + $cartItem->getQuantity());
                return $item;
            }
        }

        $this->cartItems[] = $cartItem;

        return $cartItem;
    }

    public function removeCartItem(Product $product)
    {
        /*if ($this->cartitems->removeElement($cartitem)) {
            // set the owning side to null (unless already changed)
            if ($cartitem->getCart() === $this) {
                $cartitem->setCart(null);
            }
        }*/

        foreach ($this->cartItems as $item){
            /**
             * @var CartItem $item
             */
            if($item->getProduct()->getId() == $product->getId()){

                $this->cartItems->removeElement($item);
                return $this;
            }
        }


        return $this;
    }

    public function getTotalAmount()
    {
        $totalAmount = 0;
        foreach($this->cartItems as $cartItem){
            /**
             * @var $cartItem CartItem
             */
            $totalAmount += $cartItem->getQuantity() * $cartItem->getProduct()->getPrice();
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
