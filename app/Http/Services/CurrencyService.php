<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use App\Models\Currency;

class CurrencyService
{
    use ModelHelper;

    public function getAll()
    {
        return Currency::all();
    }

    public function find($currencyId)
    {
        return $this->findByIdOrFail(Currency::class, 'currency', $currencyId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $currency = Currency::create($validatedData);

        DB::commit();

        return $currency;
    }

    public function update($validatedData, $currencyId)
    {
        $currency = $this->find($currencyId);

        DB::beginTransaction();

        $currency->update($validatedData);

        DB::commit();

        return true;
    }

    public function delete($currencyId)
    {
        $currency = $this->find($currencyId);

        DB::beginTransaction();

        $currency->delete();

        DB::commit();

        return true;
    }

    public static function currencyConvert($userCountryCode): array
    {
        DB::beginTransaction();

        $currency = Currency::where('code', $userCountryCode)->first();
        if (isset($currency)) {
            $currencyDetails = [
                'exchange_rate'  => $currency->exchange_rate,
                'symbol'         => $currency->symbol
            ];
        } else {
            $currencyDetails = [
                'exchange_rate'  => 1,
                'symbol'         => "$"
            ];
        }

        DB::commit();
        return $currencyDetails;
    }
}
