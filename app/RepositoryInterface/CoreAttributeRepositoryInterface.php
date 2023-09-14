<?php

namespace App\RepositoryInterface;
use App\Models\CoreAttribute;

interface CoreAttributeRepositoryInterface
{
    public function getAttributes();
    public function createAttribute($attributes);
    public function updateAttribute(CoreAttribute $attribute, $attributes);
    public function findAttributeById($attributeId);
    public function deleteAttribute($attributeId);
    public function getAllTermsByAttribute(CoreAttribute $attribute);
}
