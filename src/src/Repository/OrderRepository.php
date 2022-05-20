<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Order::class);
        $this->manager = $manager;
    }

    public function addOrder($product, $customer, $quantity, $address, $shippingDate)
    {
        $order = new Order();
        $orderCode = "#odr-".str_pad(rand(0, pow(10, 4)-1), 4, '0', STR_PAD_LEFT);
        $dates = new \DateTime('@'.strtotime('now'));
        $order
            ->setProduct($product)
            ->setCustomer($customer)
            ->setOrderCode($orderCode)
            ->setQuantity($quantity)
            ->setAddress($address)
            ->setShippingDate($shippingDate)
            ->setCreatedAt($dates);

        $this->manager->persist($order);
        $this->manager->flush();
    }

    public function updateOrder(Order $order): Order
    {
        $this->manager->persist($order);
        $this->manager->flush();

        return $order;
    }

    public function removeOrder(Order $order)
    {
        $this->manager->remove($order);
        $this->manager->flush();
    }
}
