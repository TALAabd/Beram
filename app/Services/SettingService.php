<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\SettingRepository;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Models\Country;
use App\Models\PaymentMethod;

class SettingService
{

    public function getAll()
    {
        $rangs          = Setting::get();
        $country        = Country::with('cities')->get();
        $paymentMethods = PaymentMethod::get();
        return [
            'rangs'          => $rangs,
            'country'        => $country,
            'payment_method' => $paymentMethods,
            ];
    }
         
    public function find($settingId)
    {
        return $this->settingRepository->find($settingId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $setting = $this->settingRepository->create($validatedData);

        DB::commit();

        return $setting;
    }

    public function update($validatedData, $settingId)
    {
        $setting = $this->settingRepository->find($settingId);

        DB::beginTransaction();

        $this->settingRepository->update($validatedData, $setting);

        DB::commit();

        return true;
    }

    public function delete($settingId)
    {
        $setting = $this->settingRepository->find($settingId);

        DB::beginTransaction();

        $this->settingRepository->delete($setting);

        DB::commit();

        return true;
    }
}
