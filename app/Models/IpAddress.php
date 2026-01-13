<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    /** @use HasFactory<\Database\Factories\IpAddressFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'label',
        'comment',
    ];
}
