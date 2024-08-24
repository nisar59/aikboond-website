<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnionCouncils extends Model
{
    use HasFactory;
     protected $fillable = ['state_id','city_id','name'];
   	 protected $table='union_councils';

}
