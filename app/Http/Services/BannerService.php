<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\BannerRepository;

class BannerService
{
    public function __construct(private BannerRepository $bannerRepository)
    {
    }

    public function getAll()
    {
        return $this->bannerRepository->getAll();
    }
    public function webBanner()
    {
        return $this->bannerRepository->webBanner();
    }
    
    public function banner1()
    {
        return $this->bannerRepository->banner1();
    }
    public function banner2()
    {
        return $this->bannerRepository->banner2();
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $banner = $this->bannerRepository->create($validatedData);

        DB::commit();

        return $banner;
    }

    public function find($bannerId)
    {
        return $this->bannerRepository->find($bannerId);
    }


    public function update($validatedData, $bannerId)
    {
        $banner = $this->bannerRepository->find($bannerId);

        DB::beginTransaction();

        $this->bannerRepository->update($validatedData, $banner);

        DB::commit();

        return $banner;
    }

    public function delete($bannerId)
    {
        $banner = $this->bannerRepository->find($bannerId);

        DB::beginTransaction();

        $this->bannerRepository->delete($banner);

        DB::commit();

        return true;
    }

    public function deleteMediaForId($bannerId, $mediaId)
    {
        DB::beginTransaction();

        $banner = $this->find($bannerId);
        $mediaItem = $banner->getMedia($banner->banner_type)->firstWhere('id', $mediaId);
        $mediaItem->delete();

        DB::commit();

        return true;
    }

    public function getBannersSection()
    {
        DB::beginTransaction();
        $bannersSection = ['section_1', 'section_2', 'website'];
        $banners = $this->bannerRepository->getAll();
        foreach ($banners as $banner) {
            if ($banner->banner_type && in_array($banner->banner_type, $bannersSection)) {
                $bannersSection = array_diff($bannersSection, [$banner->banner_type]);
            }
        }
        DB::commit();

        return array_values($bannersSection);
    }
}
