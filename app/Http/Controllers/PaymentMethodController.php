<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethodRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Services\PaymentMethodService;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function __construct(private PaymentMethodService $paymentmethodservice)
    {
    }

    public function getAll(Request $request)
    {
        $paymentmethod = $this->paymentmethodservice->getAll($request);
        return $this->successResponse(
            $this->resource($paymentmethod, PaymentMethodResource::class),
            'dataFetchedSuccessfully'
        );
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(PaymentMethodRequest $request, $payment_methodId)
    {
        $validatedData = $request->validated();
        $this->paymentmethodservice->update($validatedData, $payment_methodId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        //
    }
}
