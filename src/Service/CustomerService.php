<?php

namespace App\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Service\Infrastructure\ICustomerService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomerService implements ICustomerService
{
    protected CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return Customer[]
     */
    public function getAll(): array
    {
        return $this->customerRepository->findAll();
    }

    public function getById(int $id): Customer
    {
        return $this->customerRepository->find($id);
    }

    public function create(array $customerData): Customer
    {
        try {
            $customer = new Customer();
            $this->updateProperties($customer, $customerData);
            return $customer;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function update(Customer $customer, array $customerData): Customer
    {
        try {
            $this->updateProperties($customer, $customerData);
            return $customer;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function save(Customer $customer)
    {
        try {
            $this->customerRepository->save($customer);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function delete(Customer $customer)
    {
        try {
            $this->customerRepository->remove($customer);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteById(int $id)
    {
        try {
            $customer = $this->getById($id);
            $this->customerRepository->remove($customer);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function updateProperties(Customer $customer, array $customerData)
    {
        if (array_key_exists('name', $customerData)) {
            $customer->setName($customerData['name']);
        }

        if (array_key_exists('email', $customerData)) {
            $customer->setEmail($customerData['email']);
        }

        if (array_key_exists('password', $customerData)) {
            $customer->setPassword($customerData['password']);
        }

        if (array_key_exists('revenue', $customerData)) {
            $customer->setRevenue($customerData['revenue']);
        }
    }
}