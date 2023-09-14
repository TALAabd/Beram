<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use App\Models\BusinessSetting;

class BusinessSettingService
{
    use ModelHelper;

    public function getAll()
    {
        return BusinessSetting::filter(request()->filter)->get();
    }

    public function find($business_settingId)
    {
        return $this->findByIdOrFail(BusinessSetting::class,'business_setting', $business_settingId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $business_setting = BusinessSetting::create($validatedData);

        DB::commit();

        return $business_setting;
    }

    public function update($validatedData, $business_settingId)
    {
        $business_setting = $this->find($business_settingId);

        DB::beginTransaction();

        $business_setting->update($validatedData);

        DB::commit();

        return true;
    }

    public function delete($business_settingId)
    {
        $business_setting = $this->find($business_settingId);

        DB::beginTransaction();

        $business_setting->delete();

        DB::commit();

        return true;
    }
}
