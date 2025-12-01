<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyRequest extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'employees',
        'status',
        'notes',
    ];

    protected $casts = [
        'employees' => 'integer',
    ];
}
