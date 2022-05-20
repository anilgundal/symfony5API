<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CustomerController
 * @package App\Controller
 *
 * @Route(path="/api/customer")
 */
class CustomerController extends AbstractController
{
    private $customerRepository;
    private $companyRepository;

    public function __construct(CustomerRepository $customerRepository, CompanyRepository $companyRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @Route("/add", name="add_customer", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $phone = $data['phone'];
        $companyId = $data['companyId'];

        if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($companyId)) {
            return new JsonResponse(['status' => 'Zorunlu alanlar girilmelidir.'], Response::HTTP_NO_CONTENT);
        }

        $company = $this->companyRepository->findOneBy(['id' => $companyId]);
        $this->customerRepository->addCustomer($firstName, $lastName, $email, $phone, $company);

        return new JsonResponse(['status' => 'Müşteri ekleme işlemi başarılı.'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="get_customer", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $customer= $this->customerRepository->findOneBy(['id' => $id]);

        if (empty($customer)) {
            return new JsonResponse(['status' => 'Müşteri bulunamadı.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'customer' => $customer->toArray()
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/all", name="get_all_customer", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $customers = $this->customerRepository->findAll();
        $data = [];

        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->getId(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phone' => $customer->getPhone(),
                'company' => $customer->getCompany()->getName()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="update_customer", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['firstName']) ? true : $customer->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $customer->setLastName($data['lastName']);
        empty($data['email']) ? true : $customer->setEmail($data['email']);
        empty($data['phone']) ? true : $customer->setPhone($data['phone']);
        empty($data['companyId']) ? true : $customer->setCompany($this->companyRepository->findOneBy(['id' => $data['companyId']]));

        $updatedCustomer = $this->customerRepository->updateCustomer($customer);

        return new JsonResponse($updatedCustomer->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/delete/{id}", name="delete_customer", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);

        if (empty($customer)) {
            return new JsonResponse(['status' => 'Müşteri bulunamadı.'], Response::HTTP_NOT_FOUND);
        }

        $this->customerRepository->removeCustomer($customer);

        return new JsonResponse(['status' => 'Müşteri silindi.'], Response::HTTP_OK);
    }
}
