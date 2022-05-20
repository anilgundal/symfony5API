<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Repository\CustomerRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @package App\Controller
 *
 * @Route(path="/api/order")
 */
class OrderController extends AbstractController
{
    private $orderRepository;
    private $productRepository;
    private $customerRepository;

    #[Route('/order', name: 'app_order')]
    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository, CustomerRepository $customerRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
    }
    /**
     * @Route("/add", name="add_order", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $productId = $data['productId'];
        $customerId = $data['customerId'];
        $quantity = $data['quantity'];
        $address = $data['address'];
        $Date = $data['shippingDate'];
        $shippingDate = DateTime::createFromFormat('Y-m-d H:i:s', $Date);
        $dates = new \DateTime('@'.strtotime('now'));

        if (empty($productId) || empty($customerId) || empty($address) || empty($quantity) || empty($Date)) return new JsonResponse(['status' => 'Zorunlu alanlar girilmelidir.'], Response::HTTP_NO_CONTENT);

        if(!$this->productRepository->findOneBy(['id' => $productId])) return new JsonResponse(['status' => 'Ürün Bulunamadı!'], Response::HTTP_NOT_FOUND);
        if(!$this->customerRepository->findOneBy(['id' => $customerId])) return new JsonResponse(['status' => 'Müşteri Bulunamadı!'], Response::HTTP_NOT_FOUND);

        $product = $this->productRepository->findOneBy(['id' => $productId]);
        $customer = $this->customerRepository->findOneBy(['id' => $customerId]);

        $this->orderRepository->addOrder($product, $customer, $quantity, $address, $dates);

        return new JsonResponse(['status' => 'Siparişiniz Eklenmiştir.'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="get_order", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $order= $this->orderRepository->findOneBy(['id' => $id]);

        if (empty($order)) {
            return new JsonResponse(['status' => 'Sipariş bulunamadı.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'order' => $order->toArray(),
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/all", name="get_all_order", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $orders = $this->orderRepository->findAll();
        $data = [];

        foreach ($orders as $order) {
            $data[] = $order->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="update_order", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        if(!$this->productRepository->findOneBy(['id' => $data['productId']])) return new JsonResponse(['status' => 'Ürün Bulunamadı!'], Response::HTTP_NOT_FOUND);
        if(!$this->customerRepository->findOneBy(['id' => $data['customerId']])) return new JsonResponse(['status' => 'Müşteri Bulunamadı!'], Response::HTTP_NOT_FOUND);

        $today = date_create_from_format('Y-m-d', date('Y-m-d'));
        $shippingDate = date_create_from_format('Y-m-d', $order->getShippingDate()->format('Y-m-d'));
        $diff = (array) date_diff($today, $shippingDate);

        if($diff['d'] > 0){
            empty($data['productId']) ? true : $order->setProduct($this->productRepository->findOneBy(['id' => $data['productId']]));
            empty($data['customerId']) ? true : $order->setCustomer($this->customerRepository->findOneBy(['id' => $data['customerId']]));
            empty($data['quantity']) ? true : $order->setQuantity($data['quantity']);
            empty($data['address']) ? true : $order->setAddress($data['address']);
            empty($data['shippingDate']) ? true : $order->setShippingDate(new \DateTime('@'.strtotime($data['shippingDate'])));

            $updatedOrder = $this->orderRepository->updateOrder($order);

            return new JsonResponse($updatedOrder->toArray(), Response::HTTP_OK);
        }else{
            return new JsonResponse(['status' => 'Sipariş Günü güncelleme yapamazsınız!'], Response::HTTP_NOT_ACCEPTABLE);
        }


    }

    /**
     * @Route("/delete/{id}", name="delete_order", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id' => $id]);

        if (empty($order)) {
            return new JsonResponse(['status' => 'Sipariş bulunamadı.'], Response::HTTP_NOT_FOUND);
        }

        $this->orderRepository->removeOrder($order);

        return new JsonResponse(['status' => 'Sipariş silindi.'], Response::HTTP_OK);
    }
}
