<?php

namespace App\Core\Framework\Data;

use Spatie\LaravelData\Data;

class BrandData extends Data
{
    public function __construct(
        public string $name,
        public ?string $logoUrl = '/core/files/images/favicon.ico',
        public ?string $darkModeLogoUrl = null,
        public string $homeRoute = 'dashboard',
    ) {}
}