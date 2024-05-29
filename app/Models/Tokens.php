<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tokens extends Model
{
    use HasFactory;


    protected $table="tokens";
    protected $fillable=['user_id','token','last_used','expiry', 'payment_method','logs','payment_status'];
}
