<?php


namespace Modules\Hotels\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Models\User;
use Modules\Authentication\Models\Customer;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $locale = app()->getLocale();
        if(Auth::guard('customer')->check())
        {
           
            $user = Customer::where('id',Auth::guard('customer')->user()->id)->first();
            $price=null;
            if($user->nationality =='Syrian')
            {
              $price = $this->syrian_price;
              
            }else{
              $price = $this->foreign_price;
            }
            return [
                'lang'      => $locale,
                'id'        => $this->id,
                'title'     => $this->getTranslation('title', $locale) ?? '',
                'price'     => $price,
                'name'      => $this->hotel->name,
                'address'   => $this->hotel->address,
                'baths'     => $this->baths,
                // 'number'    => $this->number,
                'space'     => $this->size,
                'beds'      => $this->beds,
                // 'adults' => $this->adults,
                // 'children'   => $this->children,
                'media_urls' => $this->media_urls,
            ];
        }else{
            return [
                'lang' => $locale,
                'id' => $this->id,
                'title' => $this->getTranslation('title', $locale) ?? '',
                'content' => $this->getTranslation('content', $locale) ?? '',
                'status' => $this->status,
                'syrian_price' => $this->syrian_price,
                'foreign_price' => $this->foreign_price,
                'number' => $this->number,
                'space' => $this->size,
                'baths'     => $this->baths,
                'beds' => $this->beds,
                'adults' => $this->adults,
                'children' => $this->children,
                'media_urls' => $this->media_urls,
            ];
        }
       
       
    }
}
