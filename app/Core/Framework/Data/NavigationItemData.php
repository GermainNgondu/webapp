<?php

namespace App\Core\Framework\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class NavigationItemData extends Data
{
    public function __construct(
        public string $label,
        public ?string $route = null,
        public ?string $icon = null,
        public ?string $badge = null,
        public int $order = 100,
        #[DataCollectionOf(NavigationItemData::class)]
        public array|DataCollection|null $children = null,
    ) {
        if (is_array($this->children)) {
            $this->children = self::collect($this->children);

        }
    }

    /**
     * Raccourci statique toujours utile pour la lisibilité
     */
    public static function make(...$args): self
    {
        return new self(...$args);
    }
}