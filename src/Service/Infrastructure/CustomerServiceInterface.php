<?php

namespace App\Service\Infrastructure;

use App\Entity\Customer;

interface CustomerServiceInterface
{
    /**
     * @return Customer[]
     */
    public function getAll(): array;

    public function getById(int $id): Customer;

    public function create(array $customerData): Customer;

    public function update(Customer $customer, array $customerData): Customer;

    public function save(Customer $customer);

    public function delete(Customer $customer);

    public function deleteById(int $id);

    public function updateProperties(Customer $customer, array $customerData);
}