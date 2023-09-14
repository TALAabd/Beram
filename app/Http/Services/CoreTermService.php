<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\RepositoryInterface\CoreTermRepositoryInterface;

class CoreTermService
{
    private $core_termRepository;

    public function __construct(
        CoreTermRepositoryInterface $core_termRepository
    ) {
        $this->core_termRepository = $core_termRepository;
    }

    public function find($core_termId)
    {
        return $this->core_termRepository->find($core_termId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $core_term = $this->core_termRepository->create($validatedData);

        DB::commit();

        return $core_term;
    }

    public function update($validatedData, $core_termId)
    {
        $core_term = $this->core_termRepository->find($core_termId);

        DB::beginTransaction();

        $core_term = $this->core_termRepository->update($core_term,$validatedData);

        DB::commit();

        return $core_term;
    }

    public function delete($core_termId)
    {
        $core_term = $this->core_termRepository->find($core_termId);

        DB::beginTransaction();

        $this->core_termRepository->delete($core_term);

        DB::commit();

        return true;
    }
}
