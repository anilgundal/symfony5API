<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'orders')]
    private $product;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'orders')]
    private $customer;

    #[ORM\Column(type: 'string', length: 255)]
    private $orderCode;

    #[ORM\Column(type: 'smallint')]
    private $quantity;

    #[ORM\Column(type: 'text')]
    private $address;

    #[ORM\Column(type: 'date')]
    private $shippingDate;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getOrderCode(): ?string
    {
        return $this->orderCode;
    }

    public function setOrderCode(string $orderCode): self
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getShippingDate(): ?\DateTime
    {
        return $this->shippingDate;
    }

    public function setShippingDate(\DateTime $shippingDate): self
    {
        $this->shippingDate = $shippingDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'productId' => $this->getProduct()->getId(),
            'productName' => $this->getProduct()->getName(),
            'productPrice' => $this->getProduct()->getPrice(),
            'customerId' => $this->getCustomer()->getId(),
            'orderCode' => $this->getOrderCode(),
            'quantity' => $this->getQuantity(),
            'address' => $this->getAddress(),
            'shippingDate' => $this->getShippingDate(),
            'createdAt' => $this->getCreatedAt(),
        ];
    }
}
