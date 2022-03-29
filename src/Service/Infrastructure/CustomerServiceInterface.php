<?php

namespace App\Service\Infrastructure;

use App\Entity\Customer;

interface CustomerServiceInterface
{
    /**
     * @return Customer[]
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @return Customer
     */
    public function getById(int $id): Customer;

    /**
     * @param array $customerData
     * @return Customer
     */
    public function create(array $customerData): Customer;

    /**
     * @param Customer $customer
     * @param array $customerData
     * @return Customer
     */
    public function update(Customer $customer, array $customerData): Customer;

    /**
     * @param Customer $customer
     * @return mixed
     */
    public function save(Customer $customer);

    /**
     * @param Customer $customer
     * @return mixed
     */
    public function delete(Customer $customer);

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteById(int $id);

    /**
     * @param Customer $customer
     * @param array $customerData
     * @return mixed
     */
    public function updateProperties(Customer $customer, array $customerData);
}