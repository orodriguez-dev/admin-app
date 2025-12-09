<?php

namespace App\Http\Controllers;

use App\Enums\IdentificationType;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Customers/Index', [
            'customers' => Customer::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $identificationTypes = array_map(function ($type) {
            return [
                'value' => $type->value,
                'label' => $type->label(),
            ];
        }, IdentificationType::cases());

        return Inertia::render('Customers/Create', [
            'identificationTypes' => $identificationTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());

        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // return Inertia::render('Customers/Show', [
        //     'customer' => $customer,
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $identificationTypes = array_map(function ($type) {
            return [
                'value' => $type->value,
                'label' => $type->label(),
            ];
        }, IdentificationType::cases());
        
        return Inertia::render('Customers/Edit', [
            'customer' => $customer,
            'identificationTypes' => $identificationTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index');
    }
}
