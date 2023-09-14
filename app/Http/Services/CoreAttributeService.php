<?php

namespace App\Http\Services;

use App\RepositoryInterface\CoreAttributeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CoreAttributeService
{
    private $attributeRepository;

    public function __construct(
        CoreAttributeRepositoryInterface $attributeRepository
    ) {
        $this->attributeRepository = $attributeRepository;
    }

    public function getAttributes()
    {
        return $this->attributeRepository->getAttributes();
    }

    public function createAttribute($validatedRequest)
    {
        DB::beginTransaction();
        $attribute = $this->attributeRepository->createAttribute($validatedRequest);
        DB::commit();
        return $attribute;
    }

    public function find($attributeId)
    {
        return $this->attributeRepository->findAttributeById($attributeId);
    }

    public function updateAttribute($attributeId, $validatedRequest)
    {
        DB::beginTransaction();
        $attribute = $this->attributeRepository->findAttributeById($attributeId);
        DB::commit();
        return $this->attributeRepository->updateAttribute($attribute, $validatedRequest);
    }


    public function deleteAttribute($attributeId)
    {
        DB::beginTransaction();
        $this->attributeRepository->deleteAttribute($attributeId);
        DB::commit();
    }

    public function getTermsByAttribute($attributeId)
    {
        DB::beginTransaction();
        $attribute = $this->attributeRepository->findAttributeById($attributeId);
        $terms = $this->attributeRepository->getAllTermsByAttribute($attribute);
        DB::commit();
        return $terms;
    }

}
