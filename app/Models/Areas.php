<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    use HasFactory;
    protected $fillable = ['country_id','state_id','city_id','name','nearest_place'];
    protected $table='areas';

}
