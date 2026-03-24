<?php

namespace App\Core\Framework\Data;

use Spatie\LaravelData\Data;

class BrandData extends Data
{
    public function __construct(
        public string $name,
        public ?string $logoUrl = null,
        public ?string $darkModeLogoUrl = null,
        public string $homeRoute = 'admin.dashboard.index', // Where the logo click leads
    ) {}
}