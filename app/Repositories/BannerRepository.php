<?php

namespace App\Repositories;

use App\Models\Banner;
use App\Traits\ModelHelper;

class BannerRepository
{
    use ModelHelper;

    public function getAll()
    {
        return Banner::get();
    }
    public function webBanner()
    {
        return Banner::where('banner_type','website')->get();
    }
    public function banner1()
    {
        return Banner::where('banner_type','section_1')->get();
    }
    public function banner2()
    {
        return Banner::where('banner_type','section_2')->get();
    }

    public function find($bannerId)
    {
        return $this->findByIdOrFail(Banner::class,'Banner', $bannerId);
    }

    public function create($validatedData)
    {
        return Banner::create($validatedData);
    }

    public function update($validatedData, Banner $banner)
    {
        return $banner->update($validatedData);
    }

    public function delete(Banner $banner)
    {
        // $banner->getMedia($banner->type)->delete();
         $banner->clearMediaCollection($banner->banner_type);
         return $banner->delete();
    }
}
