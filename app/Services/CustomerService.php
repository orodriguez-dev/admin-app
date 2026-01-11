<?php

namespace App\Services;

use App\Enums\IdentificationType;
use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CustomerService
{
    protected CustomerRepositoryInterface $repository;

    public function __construct(CustomerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listCustomers(): Collection
    {
        return $this->repository->all();
    }

    public function getCustomer(int $id): ?Customer
    {
        return $this->repository->find($id);
    }

    public function createCustomer(array $data): Customer
    {
        return $this->repository->create($data);
    }

    public function updateCustomer(Customer $customer, array $data): bool
    {
        return $this->repository->update($customer, $data);
    }

    public function deleteCustomer(Customer $customer): bool
    {
        return $this->repository->delete($customer);
    }

    public function getIdentificationTypes(): array
    {
        return array_map(function ($type) {
            return [
                'value' => $type->value,
                'label' => $type->label(),
            ];
        }, IdentificationType::cases());
    }
}