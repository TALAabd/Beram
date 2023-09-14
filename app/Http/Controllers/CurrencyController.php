<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Services\CurrencyService;

class CurrencyController extends Controller
{
    public function __construct(private CurrencyService $currencyService)
    {
    }

    public function getAll()
    {
        $currencies = $this->currencyService->getAll();
        return $this->successResponse(
            $this->resource($currencies, CurrencyResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($currencyId)
    {
        $currency = $this->currencyService->find($currencyId);

        return $this->successResponse(
            $this->resource($currency, CurrencyResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(CurrencyRequest $request)
    {
        $validatedData = $request->validated();
        $currency = $this->currencyService->create($validatedData);

        return $this->successResponse(
            $this->resource($currency, CurrencyResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(CurrencyRequest $request, $currencyId)
    {
        $validatedData = $request->validated();
        $this->currencyService->update($validatedData, $currencyId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($currencyId)
    {
        $this->currencyService->delete($currencyId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
