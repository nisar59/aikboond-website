<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $table='settings';

    
    public function getCompensationForAttribute($value)
    {
        return (array) json_decode($value);
    }
}
