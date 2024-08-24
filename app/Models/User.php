<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AddressesAndTowns;
use App\Models\Cities;
use App\Models\Areas;
use App\Models\States;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table='donors';

    protected $fillable = ['user_id','name','phone','pin','dob','blood_group','last_donate_date','image','country_id','state_id','city_id','ucouncil_id', 'address','status'];
    protected $with=['state', 'city'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pin',
    ];


    public function state()
    {
       return $this->hasOne(States::class, 'id', 'state_id');
    }

    public function city()
    {
       return $this->hasOne(Cities::class, 'id', 'city_id');
    }

   

   public function getAuthPassword()
   {
       return $this->pin;
   }

}
