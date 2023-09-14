<?php

namespace App\RepositoryInterface;

use App\Models\CoreAttribute;
use App\Models\CoreTerm;

interface CoreTermRepositoryInterface
{
    public function find($termId);
    public function update(CoreTerm $term, $terms);
    public function create($terms);
    public function delete(CoreTerm $core_term);
}
