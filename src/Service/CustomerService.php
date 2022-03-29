<?php

namespace App\Service;

use App\Entity\Customer;
use App\Exception\NotFoundException;
use App\Repository\CustomerRepository;
use App\Service\Infrastructure\CustomerServiceInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;


class CustomerService implements CustomerServiceInterface
{
    /**
     * @var CustomerRepository
     */
    protected CustomerRepository $customerRepository;

    /**
     * @param CustomerRepository $customerRepository
     */
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

    /**
     * @param int $id
     * @return Customer
     * @throws NotFoundException
     */
    public function getById(int $id): Customer
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            throw new NotFoundException();
        }

        return $customer;
    }

    /**
     * @param array $customerData
     * @return Customer
     */
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

    /**
     * @param Customer $customer
     * @param array $customerData
     * @return Customer
     */
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

    /**
     * @param Customer $customer
     * @return void
     */
    public function save(Customer $customer)
    {
        try {
            $this->customerRepository->save($customer);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    /**
     * @param Customer $customer
     * @return void
     */
    public function delete(Customer $customer)
    {
        try {
            $this->customerRepository->remove($customer);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return void
     */
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

    /**
     * @param Customer $customer
     * @param array $customerData
     * @return void
     */
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