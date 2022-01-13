<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'=> $this->name,
            'phone'=>$this->phone,
            'email'=>$this->email,
            'account_ number' =>$this->wallet ? $this->wallet->account_number :'',
            'balance' =>$this->wallet ? number_format($this->wallet->amount) :0,
            'profile'=>asset('img/profile.png'),
            'hash_value'=>$this->phone,

        ];
    }
}
