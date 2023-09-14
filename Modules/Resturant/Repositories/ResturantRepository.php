<?php

namespace Modules\Resturant\Repositories;

use App\Models\CoreAttribute;
use Modules\Resturant\Models\Resturant;
use Illuminate\Support\Str;
use App\Traits\ModelHelper;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Models\User;

class ResturantRepository
{
    use ModelHelper;

    public function getAll($page = null, $per_page = 5)
    {
        $resturants = (isset($page)) ? Resturant::resturants()->simplePaginate($per_page)->get() :  Resturant::resturants()->get();
        return $resturants;
    }

    public function find($resturantId)
    {
        return $this->findByIdOrFail(Resturant::class, 'resturant', $resturantId);
    }

    public function create($validatedData)
    {
        $user=User::where('id',Auth::user()->id)->first();
        $validatedData['slug']= Str::slug($validatedData['name'] . '-' . Str::random(6));
        $validatedData['user_id']= ($user->role=="employee") ? $user->parent->id :$user->id;
        return Resturant::create($validatedData);
    }

    public function update($validatedData, Resturant $resturant)
    {
        return $resturant->update($validatedData);
    }

    public function delete(Resturant $resturant)
    {
        return $resturant->delete();
    }

    public function getAllTablesByResturant(Resturant $resturant)
    {
        return $resturant->tables()->get();
    }


    public function getAllTablesByResturantAvailability(Resturant $resturant)
    {
        return $resturant->tables()->where('status', '=', 'true')->get();
    }

    public function getAllMenusByResturant(Resturant $resturant)
    {
        return $resturant->menus;
    }

    public function getAttributesTermsByResturant(Resturant $resturant)
    {
        $resturantsTerms = $resturant->terms->pluck('id')->toArray();
        $attributesWithResturantTerms = CoreAttribute::with('core_terms')->where('service', 'restaurant')->get();
        $attributesWithResturantTerms->each(function ($attribute) use ($resturantsTerms) {
            $attribute->core_terms->each(function ($core_term) use ($resturantsTerms) {
                $core_term['status'] = in_array($core_term->id, $resturantsTerms);
            });
        });
        return $attributesWithResturantTerms;
    }

    public function updateTermsByResturant(Resturant $resturant, $termIds)
    {
        return $resturant->terms()->sync($termIds);
    }
}
