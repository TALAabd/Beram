<?php
namespace Modules\Authentication\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use Modules\Authentication\Models\Customer;

class CustomerService
{
    use ModelHelper;

    public function getAll()
    {
        return Customer::all();
    }

    public function find($customerId)
    {
        return $this->findByIdOrFail(Customer::class,'customer', $customerId);
    }

    public function delete($customerId)
    {
        $customer = $this->find($customerId);

        DB::beginTransaction();

        $customer->delete();

        DB::commit();

        return true;
    }
}
