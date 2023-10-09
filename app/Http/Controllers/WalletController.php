<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Http\Controllers\Controller;
use App\Http\Requests\WalletRequest;
use App\Http\Resources\WalletResource;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletservice)
    {
        $this->middleware('permission:wallet_manager', ['only' => ['getRegister','update','AddToWallet']]);
    }

    public function getAll(Request $request)
    {
        $trips = $this->walletservice->getAll($request);
        return $this->successResponse(
            $this->resource($trips, WalletResource::class),
            'dataFetchedSuccessfully'
        );
    }
    public function providerWallet(Request $request)
    {
        $trips = $this->walletservice->providerWallet($request);
        return $this->successResponse(
            $this->resource($trips, WalletResource::class),
            'dataFetchedSuccessfully'
        );
    }
    
    public function getRegister(Request $request)
    {
        $trips = $this->walletservice->getRegister($request);
        return $this->successResponse(
            $this->resource($trips, WalletResource::class),
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
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(WalletRequest $request, $wallet)
    {
        $validatedData = $request->validated();
        $trips = $this->walletservice->update($validatedData, $wallet);
        return $this->successResponse(
           null,
            'dataUpdatedSuccessfully'
        );
    }
    public function AddToWallet(WalletRequest $request, $wallet)
    {
        $validatedData = $request->validated();
        $trips = $this->walletservice->AddToWallet($validatedData, $wallet);
        return $this->successResponse(
           null,
            'dataUpdatedSuccessfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
}
