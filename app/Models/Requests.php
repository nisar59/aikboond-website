<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AddressesAndTowns;
use App\Models\Cities;
use App\Models\Areas;
use App\Models\States;

class Requests extends Model
{
    use HasFactory;
    protected $table="requests";
    protected $fillable=['user_id','blood_group','state_id','city_id','area_id','town_id', 'payment_screenshot', 'status'];



    public function state()
    {
       return $this->hasOne(States::class, 'id', 'state_id');
    }

    public function city()
    {
       return $this->hasOne(Cities::class, 'id', 'city_id');
    }

    public function area()
    {
       return $this->hasOne(Areas::class, 'id', 'area_id');
    }

    public function town()
    {
       return $this->hasOne(AddressesAndTowns::class, 'id', 'town_id');
    }
    
}
