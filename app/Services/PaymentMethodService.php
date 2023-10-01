<?php

namespace App\Services;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Repositories\PaymentMethodRepository;
use App\Traits\ModelHelper;

class PaymentMethodService
{
    use ModelHelper;
    // public function __construct(private PaymentMethodRepository $payment_methodRepository)
    // {
    // }

    public function getAll()
    {
        return PaymentMethod::get();
    }

    public function find($payment_methodId)
    {
        return $this->findByIdOrFail(PaymentMethod::class, 'payment_method', $payment_methodId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $payment_method = PaymentMethod::create($validatedData);

        DB::commit();

        return $payment_method;
    }

    public function update($validatedData, $payment_methodId)
    {
        $payment_method = $this->findByIdOrFail(PaymentMethod::class, 'payment_method', $payment_methodId);

        DB::beginTransaction();
        $payment_method->status = $validatedData['status'];
        $payment_method->save();

        DB::commit();

        return true;
    }

    public function delete($payment_methodId)
    {
        $payment_method = $this->findByIdOrFail(PaymentMethod::class, 'payment_method', $payment_methodId);

        DB::beginTransaction();

        $payment_method->delete($payment_method);

        DB::commit();

        return true;
    }

    public function createMedia($payment_methodId, $mediaFile)
    {
        $payment_method = $this->findByIdOrFail(PaymentMethod::class, 'payment_method', $payment_methodId);

        $payment_method->clearMediaCollection('payment_method');
        $payment_method->addMedia($mediaFile)->toMediaCollection('payment_method');

        return true;

    }
}
