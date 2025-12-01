<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerRequest extends Model
{
    protected $fillable = [
        'business_name',
        'category',
        'contact_person',
        'phone',
        'website',
        'status',
        'notes',
    ];
}
