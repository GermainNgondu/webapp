<?php

namespace App\Core\Framework\Support\Data\Insight\Attributes;

use Attribute;
use App\Core\Framework\Support\Data\Insight\Enums\ChartTypeInsightEnum;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Chart
{
    public function __construct(
        public string $label,
        public string $action,
        public ChartTypeInsightEnum $type = ChartTypeInsightEnum::BAR,
        public ?string $color = 'zinc',
        public bool $showLegend = true,
        public bool $fill = false,
        public int $colSpan = 6,
        public ?string $height = '300px',
    ) {}
}