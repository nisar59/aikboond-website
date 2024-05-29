<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressesAndTowns extends Model
{
    use HasFactory;
    protected $fillable = ['country_id','state_id','city_id','area_id','name'];
    protected $table='addresses-and-towns';

}
