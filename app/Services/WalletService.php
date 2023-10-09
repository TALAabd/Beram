<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletRegister;
use Illuminate\Support\Facades\DB;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Models\User;

class WalletService
{
    // public function __construct(private WalletRepository $walletRepository)
    // {
    // }

    public function getAll()
    {
        return Wallet::get();
    }
    public function providerWallet()
    {
        $user = Auth::user();
        return Wallet::where('provider_id', $user->id)->first();
    }
    
    public function getRegister()
    {
        return WalletRegister::get();
    }

    public function find($walletId)
    {
        return Wallet::find($walletId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $wallet = Wallet::create($validatedData);

        DB::commit();

        return $wallet;
    }

    public function update($validatedData, $walletId)
    {
        $wallet = Wallet::find($walletId);

        DB::beginTransaction();
        $wallet->update($validatedData);

        DB::commit();

        return true;
    }
    public function AddToWallet($validatedData, $walletId)
    {
        $wallet = Wallet::find($walletId);

        DB::beginTransaction();

        WalletRegister::create($validatedData);

        $validatedData['amount'] = $wallet->amount + $validatedData['amount'];
        $wallet->update($validatedData);

        DB::commit();

        return true;
    }

    public function delete($walletId)
    {
        $wallet = Wallet::find($walletId);

        DB::beginTransaction();

        $wallet->delete($wallet);

        DB::commit();

        return true;
    }
}
