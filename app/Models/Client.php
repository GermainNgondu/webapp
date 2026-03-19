<?php

namespace App\Models;

use App\Data\ClientData;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Client extends Model
{
    use WithData;

    protected $dataClass = ClientData::class;

    protected $fillable = [
        'name', 'type','contacts', 
        'vat_number', 'address', 'city', 'zip_code'
    ];

    protected $casts = [
        'contacts' => 'array'
    ];
}