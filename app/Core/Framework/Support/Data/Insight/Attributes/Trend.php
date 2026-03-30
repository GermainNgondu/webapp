<?php

namespace App\Core\Framework\Support\Data\Insight\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Trend
{
    public function __construct(
        public string $label,
        public ?string $action = null,
        public ?string $comparisonLabel = 'vs période précédente',
        public ?string $icon = 'trending-up',
        public ?string $color = 'zinc',
        public bool $showPercentage = true,
        public bool $reverseColor = false, // Utile si une baisse est positive (ex: taux d'erreur)
        public int $colSpan = 3,
    ) {}
}